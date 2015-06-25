<?php

namespace app\models;

use Yii;

class TblPartsBring extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_parts_bring';
    }

    public function rules()
    {
        return [
            [['parts_id', 'openid', 'update_time', 'add_time'], 'required'],
            [['parts_id', 'status', 'update_time', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    public function attributeLabels()
    {
        return [
            'parts_id' => '配件id',
            'openid' => '维修员',
            'status' => '状态',
            'update_time' => '最后操作时间',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
