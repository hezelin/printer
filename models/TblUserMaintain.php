<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_maintain".
 *
 * @property string $wx_id
 * @property string $openid
 * @property string $name
 * @property string $phone
 * @property integer $wait_repair_count
 * @property string $latitude
 * @property string $longitude
 * @property string $accuracy
 * @property string $add_time
 */
class TblUserMaintain extends \yii\db\ActiveRecord
{
    public function getUserinfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

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
            [['wx_id', 'wait_repair_count', 'add_time'], 'integer'],
            [['latitude', 'longitude', 'accuracy'], 'number'],
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
            'wait_repair_count' => '待修',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'add_time' => '添加时间',
        ];
    }
}
