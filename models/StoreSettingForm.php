<?php

namespace app\models;

use Yii;

class StoreSettingForm extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%store_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storename', 'showmenu', 'status'], 'required'],
            [['storename'], 'string', 'max' => 60],
            [['tel'],'match','pattern'=>'/^1[0-9]{10}$/','message'=>'手机号码格式错误'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storename' => '店铺名称',
            'showmenu' => '启用',
            'tel' => '一键拨号号码',
            'status' => '启用状态',
        ];
    }

}
