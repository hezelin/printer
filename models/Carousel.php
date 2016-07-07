<?php

namespace app\models;

use Yii;

class Carousel extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_carousel';
    }

    public function rules()
    {
        return [
            [['weixinid'], 'required'],
            [['weixinid', 'sort'], 'integer'],
            [['imgurl'], 'string', 'max' => 100],
            [['link', 'title'], 'string', 'max' => 300],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'weixinid' => '对应的微信公众号',
            'imgurl' => 'Imgurl',
            'link' => 'Link',
            'title' => 'Title',
            'sort' => '排序',
        ];
    }
}
