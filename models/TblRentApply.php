<?php

namespace app\models;

use Yii;

class TblRentApply extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_rent_apply';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'machine_id', 'phone', 'name', 'due_time', 'add_time'], 'required'],
            [['wx_id', 'machine_id', 'region', 'due_time', 'status', 'add_time'], 'integer'],
            [['monthly_rent', 'latitude', 'longitude', 'accuracy'], 'number'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30],
            [['address', 'apply_word'], 'string', 'max' => 200],
            [['identity_card'], 'string', 'max' => 18]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'machine_id' => '机器id',
            'phone' => '手机',
            'name' => '姓名',
            'region' => '地区',
            'address' => '地址',
            'identity_card' => '身份证号码',
            'due_time' => '过期时间',
            'status' => '状态',
            'monthly_rent' => '月租',
            'apply_word' => '备注',
            'add_time' => '申请时间',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'enable' => '是否有效',
        ];
    }
}
