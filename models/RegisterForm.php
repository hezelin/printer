<?php

namespace app\models;

use Yii;

class RegisterForm extends \yii\db\ActiveRecord
{
    public $pswd;
    public $acpassword;
    public $verifyCode;
    public $areaText;

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
            [['email', 'phone', 'name' ,'area','areaText', 'pswd', 'acpassword'], 'required'],
            [['area', 'create_time'], 'integer'],
            [['enable'], 'string'],
            [['email', 'name'], 'string', 'max' => 40],
            [['email'],'email'],
            [['email'],'unique'],
            [['phone'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式错误'],
            [['pswd'], 'string', 'length'=>[6,16]],
            [['acpassword'],'compare', 'compareAttribute'=>'pswd','message'=>'确认密码必须与设置密码相同'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户id',
            'email' => '邮箱',
            'phone' => '手机',
            'name' => '昵称',
            'password' => '密码',
            'pswd' => '密码',
            'acpassword' => '确认密码',
            'salt' => '加密盐',
            'area' => '地区id',
            'areaText' => '地区',
            'ip' => 'ip地址',
            'create_time' => '注册时间',
            'enable' => '是否有效',
            'verifyCode' => '验证码',
        ];
    }

    public function getUser()
    {
        return User::findOne($this->uid);
    }
}
