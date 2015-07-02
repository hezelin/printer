<?php

namespace app\modules\shop\models;

use Yii;

class TblShopCart extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_shop_cart';
    }

    public function rules()
    {
        return [
            [['item_id', 'openid', 'wx_id', 'create_time'], 'required'],
            [['item_id', 'item_nums', 'wx_id', 'create_time'], 'integer'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'item_id' => '产品id',
            'item_nums' => '产品数量',
            'openid' => '微信openid',
            'wx_id' => '公众号id',
            'create_time' => '创建时间',
            'enable' => 'Enable',
        ];
    }
}
