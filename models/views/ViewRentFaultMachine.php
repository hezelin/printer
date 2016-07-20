<?php

namespace app\models\views;

use Yii;

class ViewRentFaultMachine extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['machine_id'];
    }

    public static function tableName()
    {
        return 'view_rent_fault_machine';
    }

    public function rules()
    {
        return [
            [['machine_id', 'wx_id', 'due_time', 'first_rent_time', 'add_time', 'come_from', 'status', 'apply_status', 'fault_id'], 'integer'],
            [['wx_id', 'openid', 'phone', 'name', 'due_time', 'add_time'], 'required'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude'], 'number'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 500],
            [['series_id', 'model_name'], 'string', 'max' => 50],
            [['cover', 'brand_name'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'machine_id' => '分配机器',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'monthly_rent' => '月租',
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'phone' => '手机',
            'name' => '客户名称',
            'address' => '客户地址',
            'due_time' => '合同到期时间',
            'first_rent_time' => '下次收租时间',
            'add_time' => '申请时间',
            'series_id' => '客户编号',
            'latitude' => '纬度',
            'longitude' => '经度',
            'cover' => '封面图片',
            'brand_name' => '品牌名字',
            'model_name' => '机型名字',
            'come_from' => '来源',
            'status' => '状态',
            'apply_status' => '租赁状态',
            'fault_id' => '自增id',
        ];
    }
}
