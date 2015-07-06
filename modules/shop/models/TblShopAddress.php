<?php

namespace app\modules\shop\models;

use Yii;

class TblShopAddress extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_shop_address';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'name', 'phone', 'address_id', 'city', 'address', 'add_time'], 'required'],
            [['wx_id', 'address_id', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['name', 'city'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 12],
            [['address'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => '微信openid',
            'name' => '收件人',
            'phone' => '手机',
            'address_id' => '地址id',
            'city' => '城市',
            'address' => '详细地址',
            'add_time' => '添加时间',
            'enable' => 'Enable',
        ];
    }
}
