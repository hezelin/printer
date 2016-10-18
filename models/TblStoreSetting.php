<?php

namespace app\models;

use Yii;

class TblStoreSetting extends \yii\db\ActiveRecord
{
    /*
   * 关联轮播图表
   */
    public function getCarousel()
    {
        return $this->hasMany(Carousel::className(), ['weixinid' => 'wx_id'])
            ->orderBy('sort');
    }

    public static function tableName()
    {
        return 'tbl_store_setting';
    }

    public function rules()
    {
        return [
            [['wx_id', 'menu_name', 'add_time'], 'required'],
            [['wx_id', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['store_name'], 'string', 'max' => 60],
            [['menu_name', 'phone'], 'string', 'max' => 30],
            [['style'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '微信id',
            'store_name' => '店铺名称',
            'menu_name' => '微信入口名字',
            'style' => '店铺样式',
            'phone' => '客服电话',
            'address' => '联系地址',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
