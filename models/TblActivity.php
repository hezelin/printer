<?php

namespace app\models;

use Yii;

class TblActivity extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_activity';
    }

    public function rules()
    {
        return [
            [['wx_id', 'text'], 'required'],
            [['wx_id', 'created_at'], 'integer'],
            [['text'], 'string'],
            [['wx_id'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'text' => '最新活动',
            'created_at' => '创建时间',
        ];
    }
}
