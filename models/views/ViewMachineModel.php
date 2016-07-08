<?php

namespace app\models\views;

use Yii;

class ViewMachineModel extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return 'view_machine_model';
    }

    public function rules()
    {
        return [
            [['id', 'wx_id', 'model_id', 'come_from', 'status', 'maintain_count', 'rent_count', 'add_time'], 'integer'],
            [['wx_id', 'model_id', 'cover', 'add_time'], 'required'],
            [['buy_price'], 'number'],
            [['buy_date'], 'safe'],
            [['series_id'], 'string', 'max' => 50],
            [['cover', 'brand_name'], 'string', 'max' => 100],
            [['model'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '机器id',
            'wx_id' => '公众号id',
            'model_id' => '选择机型',
            'series_id' => '机身序列号',
            'buy_price' => '购买价格',
            'buy_date' => '购买时间',
            'cover' => '封面图片',
            'come_from' => '来源',
            'status' => '状态',
            'maintain_count' => '维修次数',
            'rent_count' => '租借次数',
            'add_time' => '添加时间',
            'model' => '型号',
            'brand_name' => '品牌中文',
        ];
    }
}
