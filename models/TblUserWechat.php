<?php

namespace app\models;

use Yii;


class TblUserWechat extends \yii\db\ActiveRecord
{
    public function getCount()
    {
        return $this->hasOne(TblUserCount::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    public static function tableName()
    {
        return 'tbl_user_wechat';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'nickname', 'sex', 'language', 'subscribe_time', 'subscribe'], 'required'],
            [['wx_id', 'sex', 'subscribe_time', 'subscribe'], 'integer'],
            [['openid'], 'string', 'max' => 28],
            [['nickname'], 'string', 'max' => 100],
            [['city', 'country', 'province'], 'string', 'max' => 30],
            [['language'], 'string', 'max' => 20],
            [['headimgurl'], 'string', 'max' => 145],
            [['wx_id', 'openid'], 'unique', 'targetAttribute' => ['wx_id', 'openid'], 'message' => 'The combination of 公众号id and openid has already been taken.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'openid' => 'openid',
            'nickname' => '昵称',
            'sex' => '性别',
            'city' => '城市',
            'country' => '国家',
            'province' => '省份',
            'language' => '语言',
            'headimgurl' => '头像',
            'subscribe_time' => '关注时间',
            'subscribe' => '是否关注',
        ];
    }
}
