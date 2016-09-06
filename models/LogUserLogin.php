<?php

namespace app\models;

use Yii;

class LogUserLogin extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'log_user_login';
    }

    public function rules()
    {
        return [
            [['user_id', 'group_id', 'login_time'], 'required'],
            [['user_id', 'group_id', 'login_time'], 'integer'],
            [['login_ip'], 'string', 'max' => 25],
            [['user_agent'], 'string', 'max' => 300],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'user_id' => '用户id',
            'group_id' => '群组 id',
            'login_ip' => '登录ip',
            'user_agent' => '来源',
            'login_time' => '登录时间',
        ];
    }
}
