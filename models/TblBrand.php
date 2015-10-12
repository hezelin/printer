<?php

namespace app\models;

use Yii;

class TblBrand extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_brand';
    }

    public function rules()
    {
        return [
            [['wx_id', 'name'], 'required'],
            [['wx_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['wx_id', 'name'], 'unique', 'targetAttribute' => ['wx_id', 'name'], 'message' => '品牌名字已经存在.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'wx_id' => '微信id',
            'name' => '名称',
        ];
    }
}
