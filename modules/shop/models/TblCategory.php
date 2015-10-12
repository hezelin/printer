<?php

namespace app\modules\shop\models;

use Yii;

class TblCategory extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_category';
    }

    public function rules()
    {
        return [
            [['wx_id', 'name'], 'required'],
            [['wx_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['wx_id', 'name'], 'unique', 'targetAttribute' => ['wx_id', 'name'], 'message' => '类目已经存在！']
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
