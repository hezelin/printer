<?php

namespace app\models;

use Yii;

class TblMachineModel extends \yii\db\ActiveRecord
{
    public $template_model;                     //模板型号
    public static function tableName()
    {
        return 'tbl_machine_model';
    }

    public function rules()
    {
        return [
            [['brand','model'], 'required'],
            [['type', 'print_speed', 'color_type', 'max_size', 'two_sided', 'status'], 'integer'],
            [['brand', 'brand_name'], 'string', 'max' => 100],
            [['model'], 'string', 'max' => 30],
            [['img_cover'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'brand' => '品牌',
            'brand_name' => '品牌中文',
            'model' => '型号',
            'type' => '类型',
            'img_cover' => '图片json 数据',
            'print_speed' => '打印速度',
            'color_type' => '支持彩色',
            'max_size' => '最大尺寸',
            'two_sided' => '支持双面',
            'status' => '状态',
            'template_model' => '验证型号',
        ];
    }
}
