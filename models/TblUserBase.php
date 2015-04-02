<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_base".
 *
 * @property string $uid
 * @property string $access_token
 * @property string $auth_key
 * @property string $email
 * @property integer $phone
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property integer $area
 * @property string $ip
 * @property integer $create_time
 * @property string $enable
 */
class TblUserBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'name', 'password', 'salt', 'area', 'ip', 'create_time'], 'required'],
            [['phone', 'area', 'create_time'], 'integer'],
            [['enable'], 'string'],
            [['access_token', 'auth_key'], 'string', 'max' => 200],
            [['email', 'name'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 32],
            [['salt'], 'string', 'max' => 8],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户id',
            'access_token' => 'access_token',
            'auth_key' => 'auth_key',
            'email' => '邮箱',
            'phone' => '手机',
            'name' => '昵称',
            'password' => '密码',
            'salt' => '加密盐',
            'area' => '地区',
            'ip' => 'ip地址',
            'create_time' => '注册时间',
            'enable' => '是否有效',
        ];
    }
}
