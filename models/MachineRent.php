<?php

namespace app\models;

use Yii;
use yii\web\HttpException;

class MachineRent
{
    public $machine;            // tbl_machine 句柄
    public $rent;               // tbl_rent_apply 句柄
    public $wid;

    /*
     * 构建默认的数据
     */
    public function __construct($machineId)
    {
        $this->wid = Cache::getWid();

        // 租赁资料如果存在，修改租赁租赁

        $this->machine = TblMachine::find()
            ->where(['id'=>$machineId,'wx_id'=>$this->wid])
            ->andWhere(['<','status',11])
            ->one();

        if(!$this->machine)
            throw new HttpException(401,'机器不存在');

        $this->rent = TblRentApply::find()->where(['machine_id'=>$machineId,'wx_id'=>$this->wid])->one();
        if(!$this->rent || $this->rent->status == 11)
        {
            $this->tips[] = '客户资料不存在，将新建客户资料';
            $this->rent = null;
        }

        $this->rent || $this->rent = new TblRentApply(['scenario' => 'new-call']);

        $this->machine->wx_id || $this->machine->wx_id = $this->wid;
        $this->machine->model_id || $this->machine->model_id = 1664;        // -
        $this->machine->brand || $this->machine->brand = 'wsz';             // -
        $this->machine->status = 2;                                         // 更改为已租借、已维修
        $this->machine->come_from || $this->machine->come_from = 2;         // 电话维修

//        用户租借申请表，需要补回  machine_id
        $this->rent->project_id || $this->rent->project_id = 0;
        $this->rent->wx_id || $this->rent->wx_id = $this->wid;
        $this->rent->openid || $this->rent->openid = uniqid('dh_');
        $this->rent->due_time ||$this->rent->due_time = strtotime('1 year',time());                // 10年到期
        $this->rent->first_rent_time|| $this->rent->first_rent_time = strtotime('3 month',time());        // 下次收租时间 10年
        $this->rent->rent_period || $this->rent->rent_period = 3;                                  // 5年
        $this->rent->status || $this->rent->status = 2;                                             // 1申请中，2已通过
        $this->rent->black_amount || $this->rent->black_amount = 0;
        $this->rent->colours_amount || $this->rent->colours_amount = 0;
    }

    /*
     * 保存参数
     */
    public function save()
    {
        $this->machine->load(Yii::$app->request->post());
        $this->rent->load(Yii::$app->request->post());
//        添加时间
        $this->machine->add_time = $this->rent->add_time = time();
        $this->rent->due_time = strtotime($this->rent->due_time);
//        添加机器封面图片 cover/images
        $this->machine->cover = str_replace('/s/','/m/',json_decode($this->machine->images,true)[0]);


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
            {
                $error[] = $this->rent->errors;
                throw new \Exception('系统出错--rent');
            }

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw new HttpException(401,ToolBase::arrayToString($error).$e);
        }

        return 'success';
    }
}
