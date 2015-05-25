<?php

namespace app\models;

use Yii;

class TblServiceProcess extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_service_process';
    }

    public function rules()
    {
        return [
            [['service_id', 'content', 'add_time'], 'required'],
            [['service_id', 'process', 'add_time'], 'integer'],
            [['content'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'service_id' => '维修id',
            'content' => '进度内容',
            'process' => '进度',
            'add_time' => '时间',
        ];
    }
}
