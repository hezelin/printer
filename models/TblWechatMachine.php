<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_wechat_machine".
 *
 * @property string $id
 * @property string $wx_id
 * @property string $openid
 * @property string $machine_id
 * @property integer $status
 * @property string $due_time
 * @property string $add_time
 * @property string $enable
 */
class TblWechatMachine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_wechat_machine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid', 'machine_id', 'due_time', 'add_time'], 'required'],
            [['wx_id', 'machine_id', 'status', 'due_time', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_id' => '公众号id',
            'openid' => '用户id',
            'machine_id' => '机器编号',
            'status' => '状态',
            'due_time' => '过期时间',
            'add_time' => '申请时间',
            'enable' => '是否有效',
        ];
    }
}
