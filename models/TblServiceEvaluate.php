<?php

namespace app\models;

use Yii;

class TblServiceEvaluate extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_service_evaluate';
    }

    public function rules()
    {
        return [
            [['fault_id', 'add_time'], 'required'],
            [['fault_id', 'score', 'add_time'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'fault_id' => '维修id',
            'score' => '评分',
            'add_time' => '评价时间',
        ];
    }
}
