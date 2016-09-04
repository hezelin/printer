<?php

namespace app\models;

use Yii;

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

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'add_time'], 'required'],
            [['wx_id', 'wait_repair_count', 'add_time', 'point_time'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['openid'], 'string', 'max' => 28],
            [['name'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 11],
            [['identity_card'], 'string', 'max' => 18],
            [['address'], 'string', 'max' => 250],
            [['wx_id', 'openid'], 'unique', 'targetAttribute' => ['wx_id', 'openid'], 'message' => 'The combination of 公众号id and 用户id has already been taken.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'wx_id' => '公众号id',
            'openid' => '用户id',
            'name' => '名字',
            'phone' => '手机',
            'identity_card' => '身份证号码',
            'address' => '维修员地址',
            'wait_repair_count' => '待修',
            'latitude' => '纬度',
            'longitude' => '经度',
            'point_time' => '地址更新时间',
            'add_time' => '添加时间',
        ];
    }
}
