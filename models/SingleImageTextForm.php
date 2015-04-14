<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class SingleImageTextForm extends ActiveRecord
{
    public $imagefile;   //file型

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
            [['keyword', 'title', 'matchmode', 'status'], 'required'],
            [['keyword','title'], 'string'],
            [['keyword'], 'string', 'max' => 30],
            [['imagefile'], 'file', 'extensions' => 'gif, jpg, png, bmp, jpeg'],
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
            'imagefile' => '图文消息封面',
            'status' => '启用规则',
        ];
    }
}
