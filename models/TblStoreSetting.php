<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_store_setting".
 *
 * @property string $id
 * @property string $wx_id
 * @property string $store_name
 * @property string $menu_name
 * @property string $style
 * @property string $add_time
 * @property string $enable
 */
class TblStoreSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_store_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'menu_name', 'add_time'], 'required'],
            [['id', 'wx_id', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['store_name'], 'string', 'max' => 60],
            [['menu_name'], 'string', 'max' => 20],
            [['style'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '店铺设置id',
            'wx_id' => '微信id',
            'store_name' => '店铺名称',
            'menu_name' => '微信入口名字',
            'style' => '样式',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
