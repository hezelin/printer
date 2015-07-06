<?php

namespace app\modules\shop\models;

use Yii;

class TblShopOrder extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_shop_order';
    }

    public function rules()
    {
        return [
            [['order_id', 'wx_id', 'openid', 'address_id', 'order_data', 'total_price', 'add_time'], 'required'],
            [['order_id', 'wx_id', 'address_id', 'pay_score', 'order_status', 'pay_status', 'express', 'add_time'], 'integer'],
            [['order_data', 'enable'], 'string'],
            [['freight', 'total_price'], 'number'],
            [['openid'], 'string', 'max' => 28],
            [['remark', 'check_word'], 'string', 'max' => 255],
            [['express_num'], 'string', 'max' => 18]
        ];
    }

    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'wx_id' => '公众号id',
            'openid' => '微信openid',
            'address_id' => '收件地址id',
            'order_data' => '订单json数据',
            'remark' => '用户备注',
            'check_word' => '审核失败',
            'freight' => '运费',
            'total_price' => '总价格',
            'pay_score' => '积分支付',
            'order_status' => '订单状态',
            'pay_status' => '支付状态',
            'express' => '快递方式',
            'express_num' => '快递号码',
            'add_time' => '添加时间',
            'enable' => '回收状态',
        ];
    }
}
