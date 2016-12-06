<?php

namespace app\models\views;

use Yii;

class ViewAllData extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['machine_id'];
    }

    public static function tableName()
    {
        return 'view_all_data';
    }

    public function rules()
    {
        return [
            [['rent_id', 'machine_id', 'wx_id', 'contain_paper','contain_colours', 'due_time', 'first_rent_time', 'add_time', 'apply_status', 'come_from', 'fault_id', 'status'], 'integer'],//20161205 新增：彩色张数
            [['wx_id', 'openid', 'due_time', 'add_time', 'phone', 'name'], 'required'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude'], 'number'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 500],
            [['series_id', 'model_name'], 'string', 'max' => 50],
            [['cover', 'brand_name', 'nickname'], 'string', 'max' => 100],
            [['headimgurl'], 'string', 'max' => 145],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rent_id' => '自增id',
            'machine_id' => '分配机器',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'monthly_rent' => '月租',
            'contain_paper' => '黑白张数',
            'contain_colours' => '彩色张数',//20161205 新增：包含彩色张数
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'due_time' => '合同到期时间',
            'first_rent_time' => '下次收租时间',
            'add_time' => '申请时间',
            'latitude' => '纬度',
            'longitude' => '经度',
            'phone' => '手机',
            'name' => '姓名',
            'address' => '用户地址',
            'apply_status' => '状态',
            'come_from' => '来源',
            'series_id' => '客户编号',
            'cover' => '封面图片',
            'brand_name' => '品牌名字',
            'model_name' => '机型名字',
            'fault_id' => '自增id',
            'status' => '状态',
            'nickname' => '昵称',
            'headimgurl' => '头像',
        ];
    }
}
