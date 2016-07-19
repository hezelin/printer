<?php

namespace app\models\fault;

use app\models\Cache;
use app\models\ConfigBase;
use app\models\TblMachine;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblUserMaintain;
use app\models\ToolBase;
use app\models\WxTemplate;
use Yii;

class NewCall
{
    public $machine;            // tbl_machine 句柄
    public $rent;               // tbl_rent_apply 句柄
    public $fault;              // tbl_machine_service
    public $tips = [];          // 提示资料
    public $faultStatus;        // 维修 状态,1维修
    public $wid;

    /*
     * 构建默认的数据
     */
    public function __construct()
    {
        $this->wid = Cache::getWid();

        // 维修资料状态，如果在维修过程中，或者刚刚申请中 返回
        // 机器不存在或者已删除，将新建机器资料
        // 租赁资料不存在 或者已删除，将新建客户资料
        if($machineId = Yii::$app->request->get('machine_id'))
        {
            $this->fault = TblMachineService::find()->where(['machine_id'=>$machineId])->one();
            if($this->fault && $this->fault->status < 9 && $this->fault->weixin_id == $this->wid){
                $this->faultStatus = $this->fault->status;
                return '';
            }

            $this->machine = TblMachine::find()->where(['id'=>$machineId,'wx_id'=>$this->wid])->one();
            if(!$this->machine || $this->machine->status == 11)
            {
                $this->tips[] = '机器型号 '.$machineId.' 不存在，将新建机器';
                $this->machine = null;
            }

            $this->rent = TblRentApply::find()->where(['id'=>$machineId,'wx_id'=>$this->wid])->one();
            if(!$this->rent || $this->rent->status == 11)
            {
                $this->tips[] = '客户资料不存在，将新建客户资料';
                $this->rent = null;
            }

        }

        $this->machine || $this->machine = new TblMachine(['scenario'=>'new-call']);
        $this->rent || $this->rent = new TblRentApply(['scenario' => 'new-call']);
        $this->fault = new TblMachineService();


        $this->machine->wx_id || $this->machine->wx_id = $this->wid;
        $this->machine->model_id || $this->machine->model_id = 1664;        //-
        $this->machine->brand || $this->machine->brand = 'wsz';             //-
        $this->machine->status = 2;                                         // 更改为已租借、已维修
        $this->machine->come_from || $this->machine->come_from = 3;         // 电话维修


//        用户租借申请表，需要补回  machine_id
        $this->rent->project_id || $this->rent->project_id = 0;
        $this->rent->wx_id || $this->rent->wx_id = $this->wid;
        $this->rent->openid || $this->rent->openid = uniqid('dh_');
        $this->rent->due_time ||$this->rent->due_time = strtotime('10 year',time());                // 10年到期
        $this->rent->first_rent_time|| $this->rent->first_rent_time = $this->rent->due_time;        // 下次收租时间 10年
        $this->rent->rent_period || $this->rent->rent_period = 60;                                  // 5年
        $this->rent->status || $this->rent->status = 3;                     // 1申请中，2已通过，3                                            // 租借通过

//        维修表，需要补回 machine_id
        $this->fault->weixin_id = $this->wid;
        $this->fault->from_openid = $this->rent->openid;
        $this->fault->content = json_encode(['cover'=>['/img/haoyizu.png']]);
    }

    /*
     * 保存参数
     */
    public function save()
    {
        $this->machine->load(Yii::$app->request->post());
        $this->rent->load(Yii::$app->request->post());
        $this->fault->load(Yii::$app->request->post());

        $this->fault->status = $this->fault->openid? 2:1;            // 已分配或者还没有分配
//        添加时间
        $this->machine->add_time = $this->rent->add_time = $this->fault->add_time = time();
        $this->rent->due_time = strtotime($this->rent->due_time);
        // 添加机器封面图片 cover/images
        if(!$this->machine->images)
        {
            $this->machine->images = json_encode(['/img/haoyizu.png']);
            $this->machine->cover = '/img/haoyizu.png';
        }

        $error = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if( !$this->machine->save())
            {
                $error[] = $this->machine->errors;
                throw new \Exception('系统出错--machine');
            }

            $this->rent->machine_id = $this->machine->id;
            if(!$this->rent->save())
                throw new \Exception('系统出错--rent');

            $this->fault->machine_id = $this->machine->id;
            if( !$this->fault->save() )
                throw new \Exception('系统出错--fault');


            if( $this->fault->openid ) {            // 如果电话维修任务分配给维修员
//            更改维修员 待维修计数
                $model = TblUserMaintain::findOne([
                    'wx_id' => $this->machine->wx_id,
                    'openid' => $this->fault->openid
                ]);
                $model->wait_repair_count = $model->wait_repair_count + 1;
                $model->save();
//            给维修员推送消息
                $tpl = new WxTemplate($this->machine->wx_id);
                $reason = ConfigBase::getFaultStatus($this->fault->type);
                $tpl->sendTask(
                    $this->fault->id,
                    $this->fault->openid,
                    $model->name, $reason, $this->rent->address,
                    $this->rent->name . ',' . $this->rent->phone, $this->machine->add_time, $this->fault->remark
                );
            }

            $transaction->commit();
        } catch(\Exception $e) {

            $transaction->rollBack();
            echo '<pre>';
            print_r($error);
            echo $e;
            exit;
//            return 'fail';
        }

        return 'success';
    }

    public function getMaintainer()
    {
        $data = (new \yii\db\Query())
            ->select('openid,name,wait_repair_count')
            ->from('tbl_user_maintain')
            ->where(['wx_id'=>Cache::getWid()])
            ->all();
        $tmp = [''=>'选择维修员'];
        if($data){
            foreach($data as $d)
                $tmp[$d['openid']] = $d['name'].'（待修 '.$d['wait_repair_count'].' ）';
        }
        return $tmp;
    }

    /**
     * 10进制转为62进制
     *
     * @param integer $n 10进制数值
     * @return string 62进制
     */
    public function dec62($n) {
        $base = 62;
        $index = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $ret = '';
        for($t = floor(log10($n) / log10($base)); $t >= 0; $t --) {
            $a = floor($n / pow($base, $t));
            $ret .= substr($index, $a, 1);
            $n -= $a * pow($base, $t);
        }
        return $ret;
    }
}
