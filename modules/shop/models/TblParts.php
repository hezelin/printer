<?php

namespace app\modules\shop\models;

use Yii;

class TblParts extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_parts';
    }

    public function rules()
    {
        return [
            [['wx_id', 'item_id'], 'required'],
            [['wx_id', 'item_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '配件id',
            'wx_id' => '公众号',
            'item_id' => '商品',
        ];
    }

}
