<?php

namespace app\models;

use Yii;

class TblRentApplyWithMachine extends \yii\db\ActiveRecord
{
    public function getUserInfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    public function getMachine()
    {
        return $this->hasOne(TblMachine::className(),['id'=>'machine_id']);
    }

    public function getMachineProject()
    {
        return $this->hasOne(TblMachineRentProject::className(),['id'=>'project_id']);
    }

    public static function tableName()
    {
        return 'tbl_rent_apply';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'black_white', 'project_id', 'due_time', 'phone', 'name', 'add_time','machine_id','first_rent_time', 'rent_period'], 'required'],
            [['wx_id', 'project_id', 'machine_id', 'status', 'add_time', 'black_amount', 'colours_amount'], 'integer'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude', 'accuracy'], 'number'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['colours','black_amount','colours_amount'], 'default', 'value' => 0],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 500],
            [['apply_word'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'project_id' => '租借方案',
            'machine_id' => '分配机器',
            'monthly_rent' => '月租',
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'black_amount' => '黑板读数',
            'colours_amount' => '彩色读数',
            'due_time' => '合同到期时间',
            'first_rent_time' => '下次收租时间',
            'rent_period' => '收租周期',
            'phone' => '手机',
            'name' => '姓名',
            'address' => '用户地址',
            'status' => '状态',
            'apply_word' => '备注',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'add_time' => '申请时间',
        ];
    }

    /*
     * 更改机器状态
     */
    public function updateMachineStatus($type='rent')
    {
        $machine = TblMachine::findOne($this->machine_id);

        if($type == 'rent'){
            $machine->rent_count = $machine->rent_count + 1;
            $machine->status = 2;
        }else{
            $machine->rent_count = $machine->rent_count - 1;
            $machine->status = 1;
        }
        return $machine->save();
    }
}
