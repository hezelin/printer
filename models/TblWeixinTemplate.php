<?php

namespace app\models;

use Yii;

class TblWeixinTemplate extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_weixin_template';
    }

    public function rules()
    {
        return [
            [['wx_id'], 'required'],
            [['wx_id'], 'integer'],
            [['newTask', 'score', 'dueTime', 'checkInfo', 'process', 'waitTask', 'cancel'], 'string', 'max' => 43]
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'newTask' => '维修员新任务',
            'score' => '积分变动',
            'dueTime' => '到期提醒',
            'checkInfo' => '资料审核',
            'process' => '维修进度',
            'waitTask' => '代办任务',
            'cancel' => '服务取消',
        ];
    }
}
