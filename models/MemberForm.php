<?php

namespace app\models;

use Yii;

class MemberForm extends \yii\db\ActiveRecord
{
    public $password;

    public static function tableName()
    {
        return 'tbl_user';
    }

    public function rules()
    {
        return [
            [['phone', 'name' ,'password','role','weixin_id'], 'required'],
            [['name'], 'string', 'max' => 40],
            [['phone'],'unique'],
            [['phone'],'match','pattern'=>'/^(13|15|17|18)\d{9}$/','message'=>'{attribute}格式错误'],
            [['password'], 'string', 'length'=>[6,16]],
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
            'role' => '角色',
            'weixin_id' => '公众号'
        ];
    }

    public function signup()
    {
        if ($this->validate()) {

            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->group_id = Yii::$app->user->id;
            $this->created_at = $this->updated_at = time();

            if ($this->save(false)) {
                return $this;
            }
        }

        return null;
    }
}
