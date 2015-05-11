<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_maintain".
 *
 * @property string $wx_id
 * @property string $openid
 * @property string $add_time
 */
class TblUserMaintain extends \yii\db\ActiveRecord
{
    public function getUserinfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_maintain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid', 'add_time'], 'required'],
            [['wx_id', 'add_time'], 'integer'],
            [['openid'], 'string', 'max' => 28],
            [['name'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 11],
            [['wx_id', 'openid'], 'unique', 'targetAttribute' => ['wx_id', 'openid'], 'message' => 'The combination of 公众号id and 用户id has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'openid' => '用户id',
            'name' => '名字',
            'phone' => '手机',
            'add_time' => '添加时间',
        ];
    }
}
