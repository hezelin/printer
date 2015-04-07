<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ResetpswdForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $acPassword;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'acPassword'], 'required'],
            [['newPassword','oldPassword'],'string', 'length' => [6, 16]],
            ['acPassword','compare', 'compareAttribute'=>'newPassword','message'=>'确认密码必须与设置密码相同'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'设置密码',
            'acPassword'=>'确认密码',
        ];
    }

}
