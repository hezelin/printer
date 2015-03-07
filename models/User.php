<?php

/*
 * 修改为 读取数据库登录
 */
namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'tbl_user_base';
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 32],
            [['auth_key'], 'string', 'max' => 100],
            [['access_token'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'uid' => 'ID',
            'email' => '登录名',
            'password' => '密码',
            'auth_key' => 'AuthKey',
            'access_token' => 'AccessToken',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     * 登录名为 邮箱
     */
    public static function findByUsername($email)
    {

        $user = User::find()
            ->where(['email' => $email])
            ->asArray()
            ->one();

        if($user){
            return new static($user);
        }

        return null;

    }

    /**
     * 自定义 id
     */
    public function getId()
    {
        return $this->uid;
    }

    /**
     * 自定义 auth_key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /*
     * 自定义 userName 为 数据表 name 字段
     */
    public function getUserName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * 验证密码
     * 密码规则为 md5($password.$this->salt.$this->salt)
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password . $this->salt . $this->salt);
    }
}
