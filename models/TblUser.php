<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class TblUser extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    public static function tableName()
    {
        return 'tbl_user';
    }

    public function rules()
    {
        return [
            [['phone', 'name'], 'required'],
            [['status', 'group_id', 'weixin_id', 'created_at', 'updated_at'], 'integer'],
            [['phone', 'role'], 'string', 'max' => 20],
            [['name', 'password_hash'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '用户id',
            'phone' => '手机',
            'name' => '姓名',
            'access_token' => '令牌',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'status' => '10为有效',
            'role' => '角色',
            'group_id' => '群组id',
            'weixin_id' => '微信id',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
