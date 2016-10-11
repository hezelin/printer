<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class TblShopHomeCarousel extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_shop_home_carousel';
    }

    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    public function rules()
    {
        return [
            [['wx_id', 'images'], 'required'],
            [['wx_id', 'created_at', 'updated_at'], 'integer'],
            [['images'], 'string', 'max' => 500],
            [['wx_id'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '微信id',
            'images' => '图片url',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
