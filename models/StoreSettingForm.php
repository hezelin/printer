<?php

namespace app\models;

use Yii;

class StoreSettingForm extends \yii\db\ActiveRecord
{
    //触发信息变量
    public $keyword = '微官网';
    public $matchmode = 1;
    public $title = '微官网标题';
    public $description;
    public $image = 'http://files.leiphone.com/uploads/01-5/-2/01-58-24-14.png';

    public static function tableName()
    {
        return '{{%store_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storename', 'title', 'showmenu', 'status'], 'required'],
            [['title','description'], 'string'],
            [['storename'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storename' => '店铺名称',
            'showmenu' => '快捷导航菜单',
            'status' => '启用状态',

            'matchmode' => '匹配模式',
            'keyword' => '触发关键词',
            'title' => '图文消息标题',
            'description' => '图文消息简介',
            'image' => '图文消息封面',
        ];
    }

}
