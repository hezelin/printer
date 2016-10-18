<?php

namespace app\models;

use Yii;

class RegisterForm extends \yii\db\ActiveRecord
{
    public $password;
    public $acpassword;
    public $verifyCode;

    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'name' ,'password', 'acpassword'], 'required'],
            [['name'], 'string', 'max' => 40],
            [['phone'],'unique'],
            [['phone'],'match','pattern'=>'/^(13|15|17|18)\d{9}$/','message'=>'{attribute}格式错误'],
            [['password'], 'string', 'length'=>[6,16]],
            [['acpassword'],'compare', 'compareAttribute'=>'password','message'=>'确认密码必须与设置密码相同'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户id',
            'phone' => '手机',
            'name' => '姓名',
            'password' => '密码',
            'acpassword' => '确认密码',
            'verifyCode' => '验证码',
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->name = $this->name;
            $user->phone = $this->phone;
            $user->created_at = $user->updated_at = time();
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    public function getUser()
    {
        return User::findOne($this->id);
    }
}
