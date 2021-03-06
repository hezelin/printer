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
            [['id', 'wx_id', 'machine_model_id', 'contain_paper','contain_colours', 'is_show', 'add_time'], 'integer'],//20161205 新增：包含彩色张数
            [['wx_id', 'machine_model_id', 'lowest_expense', 'black_white', 'cover', 'images', 'add_time'], 'required'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['describe'], 'string'],
            [['cover','brand','brand_name'], 'string', 'max' => 100],
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
            'contain_paper' => '黑白张数',
            'contain_colours' => '彩色张数',//20161205 新增：彩色张数
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'cover' => '封面图片',
            'images' => '方案图片',
            'is_show' => '显示',
            'describe' => '描述',
            'add_time' => '添加时间',
            'brand' => '品牌',
            'brand_name' => '品牌',
            'model' => '型号',
        ];
    }
}
