<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_rent_apply".
 *
 * @property string $id
 * @property string $wx_id
 * @property string $openid
 * @property string $machine_id
 * @property string $phone
 * @property string $name
 * @property string $region
 * @property string $address
 * @property string $due_time
 * @property integer $status
 * @property double $monthly_rent
 * @property string $apply_word
 * @property string $add_time
 * @property string $latitude
 * @property string $longitude
 * @property string $accuracy
 * @property string $enable
 */
class TblRentApply extends \yii\db\ActiveRecord
{
    public $areaText;
    public function getUserinfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    public function getMachine()
    {
        return $this->hasOne(TblMachine::className(),['id'=>'machine_id']);
    }

    public static function tableName()
    {
        return 'tbl_rent_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'openid', 'machine_id', 'phone', 'name', 'due_time', 'add_time'], 'required'],
            [['wx_id', 'machine_id', 'region', 'due_time', 'status', 'add_time'], 'integer'],
            [['monthly_rent', 'latitude', 'longitude', 'accuracy'], 'number'],
            [['enable'], 'string'],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30],
            [['address', 'apply_word'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'machine_id' => '机器id',
            'phone' => '手机',
            'name' => '姓名',
            'region' => '地区',
            'address' => '详情地址',
            'due_time' => '过期时间',
            'status' => '状态',
            'monthly_rent' => '月租',
            'apply_word' => '备注',
            'add_time' => '申请时间',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'enable' => '是否有效',
            'areaText'=>'地区'
        ];
    }
}
