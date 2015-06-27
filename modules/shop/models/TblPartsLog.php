<?php

namespace app\modules\shop\models;

use Yii;

class TblPartsLog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_parts_log';
    }

    public function rules()
    {
        return [
            [['parts_id', 'content', 'status', 'add_time'], 'required'],
            [['parts_id', 'status', 'add_time'], 'integer'],
            [['content'], 'string', 'max' => 300]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'parts_id' => '配件id',
            'content' => '内容',
            'status' => '状态',
            'add_time' => '添加时间',
        ];
    }
}
