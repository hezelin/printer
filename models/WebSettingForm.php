<?php

namespace app\models;

use Yii;

class WebSettingForm extends \yii\db\ActiveRecord
{
    public $webname;
    public $showmenu = 1;

    public $keyword = '微官网';
    public $matchmode = 1;
    public $title = '微官网';
    public $description;
    public $image;

    public static function tableName()
    {
        return '{{%web_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['webname', 'weixinid', 'title'], 'required'],
            [['weixinid'], 'integer'],
            [['title','description'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['weixinid'],'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'weixinid' => '微信标识',
            'title' => '网站标题',
            'description' => '网站描述',
            'status' => '启用状态',

            'webname' => '微网站名称',
            'showmenu' => '启用快捷导航菜单',
            'keyword' => '触发关键词',
            'matchmode' => '匹配模式',
            'title2' => '图文消息标题',
            'description2' => '图文消息简介',
            'image' => '图文消息封面',
        ];
    }

}
