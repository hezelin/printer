<?php

namespace app\models;

use Yii;


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
            [['wx_id', 'machine_id', 'region', 'status', 'add_time'], 'integer'],
            [['enable'], 'string'],
            [['monthly_rent'], 'number'],
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
            'add_time' => '申请时间',
            'enable' => '是否有效',
            'monthly_rent' => '月租',
            'apply_word' => '备注',
            'areaText'=>'地区'
        ];
    }
}
