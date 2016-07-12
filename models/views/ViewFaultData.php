<?php

namespace app\models\views;

use Yii;

class ViewFaultData extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return 'view_fault_data';
    }

    public function rules()
    {
        return [
            [['id', 'weixin_id', 'machine_id', 'type', 'status', 'add_time', 'maintain_count', 'user_id'], 'integer'],
            [['weixin_id', 'machine_id', 'add_time'], 'required'],
            [['desc'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 600],
            [['openid'], 'string', 'max' => 28],
            [['remark', 'cover', 'brand_name'], 'string', 'max' => 100],
            [['series_id', 'model_name'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'weixin_id' => '微信id',
            'machine_id' => '机器id',
            'type' => '故障类型',
            'status' => '状态',
            'desc' => '故障描述',
            'content' => '内容（cover,voice)',
            'openid' => '维修员id',
            'remark' => '备注',
            'add_time' => '添加时间',
            'maintain_count' => '维修次数',
            'series_id' => '机身序列号',
            'cover' => '封面图片',
            'brand_name' => '品牌名字',
            'model_name' => '机型名字',
            'user_id' => '自增id',
            'user_name' => '姓名',
        ];
    }
}
