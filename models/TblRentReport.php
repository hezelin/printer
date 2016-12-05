<?php

namespace app\models;

use Yii;

class TblRentReport extends \yii\db\ActiveRecord
{
    public $next_rent;

    public function getRentApply()
    {
        return $this->hasOne(TblRentApply::className(), ['machine_id' => 'machine_id'])->onCondition('tbl_rent_apply.status < 11');
    }

    public static function tableName()
    {
        return 'tbl_rent_report';
    }

    public function rules()
    {
        return [
            [['wx_id', 'colour', 'black_white', 'status', 'machine_id', 'add_time'], 'integer'],
            [['wx_id', 'black_white','colour', 'total_money', 'sign_img', 'name', 'machine_id', 'add_time','next_rent'], 'required'],
            [['total_money', 'exceed_money'], 'number'],
            [['colour'],'default','value'=>0],
            [['sign_img'], 'string', 'max' => 80],
            [['name'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'machine_id' => '机器编号',
            'colour' => '彩色张数',
            'black_white' => '黑白张数',
            'total_money' => '收取租金',
            'exceed_money' => '超出金额',
            'sign_img' => '签名图片',
            'name' => '经手人',
            'status' => '状态',
            'add_time' => '添加时间',
            'next_rent' => '下次收租时间'
        ];
    }
}
