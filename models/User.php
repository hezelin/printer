<?php
/*
 * 修改为 读取数据库登录
 */

namespace app\models;
use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return 'tbl_user';
    }

    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['auth_key'], 'string', 'max' => 100],
            [['access_token'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => '登录名',
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
    public static function findByUsername($phone)
    {

        $user = User::find()
            ->where(['phone' => $phone])
            ->asArray()
            ->one();

        if($user){
            return new static($user);
        }

        return null;

    }

    /*
     * 查找用户通过 手机
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 自定义 id
     */
    public function getId()
    {
        return $this->id;
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

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
