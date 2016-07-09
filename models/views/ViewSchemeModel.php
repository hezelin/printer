<?php

namespace app\models\views;

use Yii;

class ViewSchemeModel extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }
    public static function tableName()
    {
        return 'view_scheme_model';
    }

    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_model_id', 'is_show', 'add_time'], 'integer'],
            [['wx_id', 'machine_model_id', 'lowest_expense', 'black_white', 'cover', 'images', 'add_time'], 'required'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['describe'], 'string'],
            [['cover', 'brand_name'], 'string', 'max' => 100],
            [['images'], 'string', 'max' => 500],
            [['model'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '方案id',
            'wx_id' => '微信id',
            'machine_model_id' => '机型',
            'lowest_expense' => '月租金',
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'cover' => '封面图片',
            'images' => '方案图片',
            'is_show' => '显示',
            'describe' => '描述',
            'add_time' => '添加时间',
            'brand_name' => '品牌',
            'model' => '型号',
        ];
    }
}
