<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_wechat".
 *
 * @property string $wx_id
 * @property string $openid
 * @property string $nickname
 * @property integer $sex
 * @property string $city
 * @property string $country
 * @property string $province
 * @property string $language
 * @property string $headimgurl
 * @property string $subscribe_time
 * @property integer $subscribe
 */
class TblUserWechat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_wechat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid', 'nickname', 'sex', 'city', 'country', 'province', 'language', 'headimgurl', 'subscribe_time', 'subscribe'], 'required'],
            [['wx_id', 'sex', 'subscribe_time', 'subscribe'], 'integer'],
            [['openid'], 'string', 'max' => 28],
            [['nickname'], 'string', 'max' => 100],
            [['city', 'country', 'province'], 'string', 'max' => 30],
            [['language'], 'string', 'max' => 20],
            [['headimgurl'], 'string', 'max' => 140],
            [['wx_id', 'openid'], 'unique', 'targetAttribute' => ['wx_id', 'openid'], 'message' => 'The combination of 公众号id and openid has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
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
