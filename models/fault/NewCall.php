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
    public $wid;

    /*
     * 构建默认的数据
     */
    public function __construct()
    {
        $this->machine = new TblMachine();
        $this->rent = new TblRentApply(['scenario' => 'new-call']);
        $this->fault = new TblMachineService();
        $this->wid = Cache::getWid();

        $this->machine->wx_id = $this->wid;
        $this->machine->model_id = 0;
        $this->machine->series_id = 'DH_'.$this->wid.'_'.$this->dec62(time());
        $this->machine->buy_date = date('Y-m-d',time());
        $this->machine->buy_price = 0;
        $this->machine->come_from = 3;
        $this->machine->status = 2;

//        用户租借申请表，需要补回  machine_id
        $this->rent->project_id = 0;
        $this->rent->wx_id = $this->wid;
        $this->rent->openid = $this->machine->series_id;
        $this->rent->due_time = strtotime('10 year',time());        // 10年到期
        $this->rent->first_rent_time = $this->rent->due_time;       // 下次收租时间 10年
        $this->rent->rent_period = 3;                               // 3个月
        $this->rent->status = 2;                                    // 租借通过

//        维修表，需要补回 machine_id
        $this->fault->weixin_id = $this->wid;
        $this->fault->from_openid = $this->rent->openid;
        $this->fault->content = json_encode(['cover'=>['/images/call_maintain.png']]);
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

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if( !$this->machine->save())
                throw new \Exception('系统出错--machine');

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
            echo $e;
            $transaction->rollBack();
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
