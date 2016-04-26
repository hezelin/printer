<?php

namespace app\models;

use Yii;

class TblQrcodeSetting extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_qrcode_setting';
    }

    public function rules()
    {
        return [
            [['wx_id', 'bg_img', 'add_time'], 'required'],
            [['wx_id', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['bg_img'], 'string', 'max' => 200],
            [['series', 'apply', 'user_name', 'code'], 'string', 'max' => 300]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'bg_img' => '背景图片',
            'series' => '编号参数',
            'apply' => '用户编号',
            'user_name' => '用户名字',
            'code' => '二维码参数',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
