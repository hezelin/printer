<?php

namespace app\modules\shop\models;

use Yii;

class TblProduct extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_product';
    }

    public function rules()
    {
        return [
            [['wx_id', 'category_id', 'name', 'cover', 'cover_images', 'market_price', 'price', 'amount', 'describe', 'add_attr', 'add_time'], 'required'],
            [['wx_id', 'category_id', 'amount', 'use_score', 'give_score', 'add_time', 'opera_time', 'status'], 'integer'],
            [['market_price', 'price'], 'number'],
            [['describe', 'enable'], 'string'],
            [['name'], 'string', 'max' => 120],
            [['cover'], 'string', 'max' => 50],
            [['cover_images'], 'string', 'max' => 300],
            [['use_score','give_score'],'default','value'=>0],
            [['add_attr'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'category_id' => '所属类目',
            'name' => '名称',
            'cover' => '封面图片',
            'cover_images' => '封面图片',
            'market_price' => '进货价格',
            'price' => '销售价格',
            'amount' => '商品数量',
            'use_score' => '可使用积分',
            'give_score' => '赠送积分',
            'describe' => '产品描述',
            'add_attr' => '商品规格',
            'add_time' => '添加时间',
            'opera_time' => '操作时间',
            'status' => '状态',
            'enable' => '是否有效',
        ];
    }
}
