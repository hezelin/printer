<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_weixin".
 *
 * @property string $id
 * @property string $uid
 * @property string $name
 * @property string $wx_num
 * @property string $app_id
 * @property string $app_secret
 * @property string $due_time
 * @property integer $try_time
 * @property integer $pay_time
 * @property double $pay_total
 * @property integer $status
 * @property integer $vip_level
 * @property string $create_time
 * @property string $enable
 */
class TblWeixin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_weixin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'wx_num', 'app_id', 'app_secret', 'due_time', 'create_time'], 'required'],
            [['uid', 'due_time', 'try_time', 'pay_time', 'status', 'vip_level', 'create_time'], 'integer'],
            [['pay_total'], 'number'],
            [['enable'], 'string'],
            [['name', 'wx_num'], 'string', 'max' => 100],
            [['app_id'], 'string', 'max' => 18],
            [['app_secret'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'uid' => '用户id',
            'name' => '公众号名字',
            'wx_num' => '微信号',
            'app_id' => 'AppID',
            'app_secret' => 'AppSecret',
            'due_time' => '到期时间',
            'try_time' => '试用次数',
            'pay_time' => '付款次数',
            'pay_total' => '总付款金额',
            'status' => '状态',
            'vip_level' => '会员套餐',
            'create_time' => '创建时间',
            'enable' => '是否有效',
        ];
    }
}
