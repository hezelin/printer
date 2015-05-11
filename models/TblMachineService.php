<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_machine_service".
 *
 * @property string $id
 * @property string $machine_id
 * @property string $from_openid
 * @property string $openid
 * @property integer $type
 * @property integer $status
 * @property string $cover
 * @property string $desc
 * @property string $add_time
 * @property string $enable
 */
class TblMachineService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_machine_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['machine_id', 'from_openid', 'desc', 'add_time'], 'required'],
            [['machine_id', 'type', 'status', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['from_openid', 'openid'], 'string', 'max' => 28],
            [['cover'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'machine_id' => '机器id',
            'from_openid' => '申请者openid',
            'openid' => '维修员id',
            'type' => '故障类型',
            'status' => '状态',
            'cover' => '封面',
            'desc' => '故障描述',
            'add_time' => '添加时间',
            'enable' => '是否使用',
        ];
    }
}
