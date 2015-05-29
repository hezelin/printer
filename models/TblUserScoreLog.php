<?php

namespace app\models;

use Yii;

class TblUserScoreLog extends \yii\db\ActiveRecord
{
    public function getUserinfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    public static function tableName()
    {
        return 'tbl_user_score_log';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'score', 'add_time'], 'required'],
            [['wx_id', 'score', 'type', 'add_time'], 'integer'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'openid' => 'openid',
            'score' => '变化积分',
            'type' => '来源',
            'add_time' => '加入时间',
        ];
    }
}
