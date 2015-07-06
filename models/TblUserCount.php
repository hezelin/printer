<?php

namespace app\models;

use Yii;

class TblUserCount extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_user_count';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid'], 'required'],
            [['wx_id', 'score'], 'integer'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '微信id',
            'openid' => 'openid',
            'score' => '积分',
        ];
    }
}
