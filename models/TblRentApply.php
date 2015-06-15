<?php

namespace app\models;

use Yii;

class TblRentApply extends \yii\db\ActiveRecord
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
            [['wx_id', 'openid', 'project_id', 'due_time', 'phone', 'name', 'add_time'], 'required'],
            [['wx_id', 'project_id', 'machine_id', 'status', 'add_time'], 'integer'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude', 'accuracy'], 'number'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
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
            'due_time' => '过期时间',
            'phone' => '手机',
            'name' => '姓名',
            'address' => '用户地址',
            'status' => '状态',
            'apply_word' => '备注',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'add_time' => '申请时间',
            'enable' => '是否有效',
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
//            $machine->rent_count = $machine->rent_count - 1;
            $machine->status = 1;
        }
        return $machine->save();
    }
}
