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
 * @property string $status
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
            [['id', 'store_name', 'wx_id', 'menu_name', 'style', 'status', 'add_time'], 'required'],
            [['id', 'wx_id', 'add_time'], 'integer'],
            [['status', 'enable'], 'string'],
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
            'id' => 'ID',
            'wx_id' => '微信ID',
            'store_name' => '店铺名称',
            'menu_name' => '微信入口菜单名',
            'style' => '样式选择',
            'status' => '店铺开关',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
