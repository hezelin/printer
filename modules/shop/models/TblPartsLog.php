<?php

namespace app\models;

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
            [['parts_id', 'remark', 'add_time'], 'required'],
            [['parts_id', 'add_time'], 'integer'],
            [['remark'], 'string', 'max' => 250]
        ];
    }

    public function attributeLabels()
    {
        return [
            'parts_id' => '配件id',
            'remark' => '标记内容',
            'add_time' => '添加时间',
        ];
    }
}
