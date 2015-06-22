<?php

namespace app\models;

use Yii;

class TblMachineModel extends \yii\db\ActiveRecord
{
    public function getBrand()
    {
        return $this->hasOne(TblBrand::className(), ['id' => 'brand_id']);
    }
    public static function tableName()
    {
        return 'tbl_machine_model';
    }

    public function rules()
    {
        return [
            [['wx_id', 'brand_id', 'type', 'cover', 'cover_images', 'buy_date', 'add_time', 'describe'], 'required'],
            [['wx_id', 'brand_id', 'add_time', 'machine_count', 'is_color'], 'integer'],
            [['buy_date'], 'safe'],
            [['describe', 'enable'], 'string'],
            [['type', 'cover'], 'string', 'max' => 52],
            [['cover_images', 'function'], 'string', 'max' => 300],
            [['else_attr'], 'string', 'max' => 1000],
            [['else_attr'], 'default', 'value' => '[]']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '系统id',
            'wx_id' => '公众号id',
            'brand_id' => '机器品牌',
            'type' => '机型型号',
            'cover' => '封面',
            'cover_images' => '封面图片',
            'buy_date' => '购买日期',
            'add_time' => '添加时间',
            'function' => '功能特征',
            'else_attr' => '补充属性',
            'describe' => '更多描述',
            'machine_count' => '机器数量',
            'is_color' => '彩色/黑板',
            'enable' => '是否有效',
        ];
    }
}
