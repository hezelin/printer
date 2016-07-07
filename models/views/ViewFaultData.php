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
            [['id', 'weixin_id', 'type', 'status', 'add_time', 'maintain_count', 'user_id'], 'integer'],
            [['weixin_id', 'add_time'], 'required'],
            [['desc'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 600],
            [['cover'], 'string', 'max' => 52],
            [['name'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'weixin_id' => '微信id',
            'type' => '故障类型',
            'status' => '状态',
            'desc' => '故障描述',
            'content' => '内容（cover,voice)',
            'add_time' => '添加时间',
            'maintain_count' => '维修次数',
            'cover' => '封面',
            'name' => '名称',
            'user_id' => '自增id',
            'user_name' => '姓名',
        ];
    }
}
