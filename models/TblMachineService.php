<?php

namespace app\models;

use Yii;

class TblMachineService extends \yii\db\ActiveRecord
{
    public function getMachine()
    {
        return $this->hasOne(TblMachine::className(), ['id' => 'machine_id']);
    }

    public static function tableName()
    {
        return 'tbl_machine_service';
    }

    public function rules()
    {
        return [
            [['machine_id', 'from_openid', 'desc', 'add_time'], 'required'],
            [['machine_id', 'type', 'status', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['from_openid', 'openid'], 'string', 'max' => 28],
            [['cover'], 'string', 'max' => 500],
            [['desc'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'machine_id' => '机器id',
            'from_openid' => '申请者openid',
            'openid' => '维修员id',
            'type' => '故障类型',
            'status' => '状态',
            'cover' => '封面',
            'desc' => '故障描述',
            'add_time' => '添加时间',
            'enable' => '是否使用',
        ];
    }
}
