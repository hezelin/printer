<?php

namespace app\models\views;

use Yii;

class ViewChargeReport extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return 'view_charge_report';
    }

    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_id', 'colour', 'black_white', 'status', 'add_time', 'first_rent_time', 'rent_period'], 'integer'],
            [['wx_id', 'machine_id', 'black_white', 'total_money', 'sign_img', 'name', 'add_time'], 'required'],
            [['total_money', 'exceed_money'], 'number'],
            [['sign_img'], 'string', 'max' => 80],
            [['name', 'brand_name'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 500],
            [['model_name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'machine_id' => '机器id',
            'colour' => '彩色张数',
            'black_white' => '黑白张数',
            'total_money' => '收取租金',
            'exceed_money' => '超出金额',
            'sign_img' => '签名图片',
            'name' => '经手人',
            'status' => '状态',
            'add_time' => '添加时间',
            'user_name' => '姓名',
            'address' => '用户地址',
            'first_rent_time' => '下次收租时间',
            'rent_period' => '收租周期',
            'model_name' => '机型',
            'brand_name' => '品牌',
        ];
    }
}
