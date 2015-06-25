<?php

namespace app\models;

use Yii;

class TblPartsFault extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_parts_fault';
    }

    public function rules()
    {
        return [
            [['parts_id', 'fault_id', 'update_time', 'add_time'], 'required'],
            [['parts_id', 'fault_id', 'status', 'update_time', 'add_time'], 'integer'],
            [['enable'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'parts_id' => '配件id',
            'fault_id' => '维修id',
            'status' => '状态',
            'update_time' => '最后操作时间',
            'add_time' => '添加时间',
            'enable' => '是否有效',
        ];
    }
}
