<?php

namespace app\models\views;

use Yii;

class ViewFaultCancel extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return 'view_fault_cancel';
    }

    public function rules()
    {
        return [
            [['id', 'wx_id', 'service_id', 'status', 'type', 'add_time', 'fault_type', 'apply_time'], 'integer'],
            [['wx_id', 'service_id', 'opera', 'add_time'], 'required'],
            [['opera'], 'string', 'max' => 28],
            [['opera_name'], 'string', 'max' => 50],
            [['reason', 'desc'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 600],
            [['remark'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'service_id' => '维修id',
            'status' => '删除进度',
            'opera' => '操作者',
            'opera_name' => '取消者',
            'type' => '系统/用户',
            'add_time' => '添加时间',
            'reason' => '取消原因',
            'fault_type' => '故障类型',
            'content' => '内容（cover,voice)',
            'desc' => '故障描述',
            'apply_time' => '添加时间',
            'remark' => '备注',
        ];
    }
}
