<?php

namespace app\models;

use Yii;

class TblNotifyLog extends \yii\db\ActiveRecord
{
    /*
     * 微信用户资料
     */
    public function getUserinfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    /*
     * 发布者
     */
    public function getFromsend()
    {
        return $this->hasOne(TblUserBase::className(), ['uid' => 'from_id']);
    }

    public static function tableName()
    {
        return 'tbl_notify_log';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'from_id', 'text', 'add_time'], 'required'],
            [['wx_id', 'from_id', 'add_time'], 'integer'],
            [['is_read', 'enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['text'], 'string', 'max' => 500]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => '接收者',
            'from_id' => '来源者',
            'text' => '通知内容',
            'is_read' => '是否已读',
            'add_time' => '发布时间',
            'enable' => '是否有效',
        ];
    }
}
