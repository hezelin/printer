<?php

namespace app\modules\shop\models;

use app\models\TblMachineService;
use app\models\TblUserMaintain;
use app\models\TblUserWechat;
use Yii;

class TblParts extends \yii\db\ActiveRecord
{
    /*
     * 关联商品
     */
    public function getProduct()
    {
        return $this->hasOne(TblProduct::className(), ['id' => 'item_id']);
    }

    /*
     * 关联维修员
     */
    public function getMaintainer()
    {
        //20161228 biao 维修员表：新增状态表
        return $this->hasOne(TblUserMaintain::className(),['openid'=>'openid'])->where(['tbl_user_maintain.status' => 10]);
    }

    /*
     * 关联维修
     */
    public function getFault()
    {
        return $this->hasOne(TblMachineService::className(),['id'=>'fault_id']);
    }

    public static function tableName()
    {
        return 'tbl_parts';
    }

    public function rules()
    {
        return [
            [['wx_id', 'item_id', 'openid'], 'required'],
            [['wx_id', 'item_id', 'fault_id', 'machine_id', 'status', 'apply_time', 'bing_time'], 'integer'],
            [['enable'], 'string'],
            [['un'], 'string', 'max' => 13],
            [['openid'], 'string', 'max' => 28],
            [['remark'], 'string', 'max' => 300]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '配件id',
            'un' => 'uniqid',
            'wx_id' => '公众号',
            'item_id' => '商品',
            'openid' => 'Openid',
            'fault_id' => 'Fault ID',
            'machine_id' => '机器编号',
            'status' => '状态',
            'remark' => '备注',
            'apply_time' => '申请时间',
            'bing_time' => '绑定时间',
            'enable' => '是否有效',
        ];
    }

    /*
     * 绑定成功，更改绑定完成时间 和 未完成配件数量
     */
    public function updateBind()
    {
        $machine = TblMachineService::findOne($this->fault_id);
        if( $machine->unfinished_parts_num > 0)
            $machine->unfinished_parts_num = $machine->unfinished_parts_num - 1;
        $machine->parts_arrive_time = time();
        return $machine->save();
    }

    /*
     * 申请配件，更改申请配件时间  和 未完成配件数量
     */
    public function updateApply()
    {
        $machine = TblMachineService::findOne($this->fault_id);
        $machine->unfinished_parts_num = $machine->unfinished_parts_num + 1;
        $machine->parts_apply_time = time();
        return $machine->save();
    }

    public $process = [
        1=>'申请中',
        2=>'携带中',
        3=>'发送中',
        4=>'已到达',
        10=>'已绑定',
        11=>'已取消',
        12=>'已回收',
        13=>'备注',
    ];
}
