<?php

namespace app\models;

use Yii;

class TblMachineRentProject extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_machine_rent_project';
    }

    public function rules()
    {
        return [
            [['wx_id', 'machine_model_id', 'lowest_expense', 'black_white', 'add_time'], 'required'],
            [['wx_id', 'machine_model_id', 'is_show', 'add_time'], 'integer'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['describe'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_id' => '公众号id',
            'machine_model_id' => '机器型号id',
            'lowest_expense' => '最低消费',
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'is_show' => '是否显示',
            'describe' => '方案描述',
            'add_time' => '添加时间',
        ];
    }
}
