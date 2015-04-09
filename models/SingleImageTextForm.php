<?php

namespace app\models;

use Yii;

class SingleImageTextForm extends \yii\db\ActiveRecord
{
//    public $keyword = '微官网';
//    public $matchmode = 1;
//    public $title = '微官网标题';
//    public $description;
//    public $image = 'http://files.leiphone.com/uploads/01-5/-2/01-58-24-14.png';

    public static function tableName()
    {
        return '{{%keyword}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'title', 'matchmode'], 'required'],
            [['keyword','title'], 'string'],
            [['keyword'], 'string', 'max' => 30],
            [['image'], 'file', 'extensions' => 'gif, jpg, png, bmp, jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keyword' => '触发关键词',
            'matchmode' => '匹配模式',
            'title' => '图文消息标题',
            'description' => '图文消息简介',
            'image' => '图文消息封面',
        ];
    }
}
