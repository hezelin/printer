<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_rent_apply".
 *
 * @property string $id
 * @property string $wx_id
 * @property string $openid
 * @property string $machine_id
 * @property string $phone
 * @property string $name
 * @property integer $status
 * @property string $add_time
 * @property string $enable
 */
class TblRentApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rent_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid', 'machine_id', 'phone', 'name', 'add_time'], 'required'],
            [['wx_id', 'machine_id', 'status', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'machine_id' => '机器id',
            'phone' => '手机',
            'name' => '姓名',
            'status' => '状态',
            'add_time' => '申请时间',
            'enable' => '是否有效',
        ];
    }
}
