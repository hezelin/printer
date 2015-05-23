<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_count".
 *
 * @property string $wx_id
 * @property string $openid
 * @property string $score
 */
class TblUserCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid'], 'required'],
            [['wx_id', 'score'], 'integer'],
            [['openid'], 'string', 'max' => 28]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wx_id' => '微信id',
            'openid' => 'openid',
            'score' => '积分',
        ];
    }
}
