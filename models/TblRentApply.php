<?php

namespace app\models;

use app\models\common\Debug;
use Yii;

class TblRentApply extends \yii\db\ActiveRecord
{
    public function getUserInfo()
    {
        return $this->hasOne(TblUserWechat::className(), ['wx_id' => 'wx_id','openid'=>'openid']);
    }

    public function getMachine()
    {
        return $this->hasOne(TblMachine::className(),['id'=>'machine_id']);
    }

    // 去掉重复
    public function getMachineFault2()
    {
        return $this->hasOne(TblMachineService::className(),['machine_id'=>'machine_id'])->onCondition('tbl_machine_service.status < 9');
    }

    public function getMachineProject()
    {
        return $this->hasOne(TblMachineRentProject::className(),['id'=>'project_id']);
    }

    public static function tableName()
    {
        return 'tbl_rent_apply';
    }

    public function rules()
    {
        return [
            [['wx_id', 'openid', 'project_id', 'due_time', 'phone', 'name', 'add_time'], 'required'],
            [['wx_id', 'project_id', 'machine_id', 'status', 'add_time', 'rent_period', 'contain_paper','contain_colours', 'black_amount','colours_amount'], 'integer'],//20161205 新增：包含彩色纸张数
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude', 'accuracy'], 'number'],
            [['colours','monthly_rent','black_white'], 'default','value'=>0],
            [['openid'], 'string', 'max' => 28],
            [['phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 500],
            [['address'], 'required', 'on' => 'new-call'],
            [['apply_word'], 'string', 'max' => 200]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'openid' => 'Openid',
            'project_id' => '租借方案',
            'machine_id' => '分配机器',
            'monthly_rent' => '月租',
            'contain_paper' => '黑白张数',
            'contain_colours' => '彩色张数',//20161205 新增：包含彩色张数
            'black_white' => '黑白价格',
            'black_amount' => '黑白读数',
            'colours_amount' => '彩色读数',
            'colours' => '彩色价格',
            'due_time' => '合同到期时间',
            'first_rent_time' => '下次收租时间',
            'rent_period' => '收租周期',
            'phone' => '手机',
            'name' => '客户名称',
            'address' => '客户地址',
            'status' => '状态',
            'apply_word' => '备注',
            'latitude' => '纬度',
            'longitude' => '经度',
            'accuracy' => '精确度',
            'add_time' => '申请时间',
        ];
    }

    /*
     * 更改机器状态
     */
    public function updateMachineStatus($type='rent')
    {
        $machine = TblMachine::findOne($this->machine_id);
        if($machine == null)
        {
            return true;
        }
        if($type == 'rent'){
            $machine->rent_count = $machine->rent_count + 1;
            $machine->status = 2;
        }else{
//            $machine->rent_count = $machine->rent_count - 1;
            $machine->status = 1;
        }

        //[20161207 保存状态前添加一个判断
        if(empty($machine->images))
            $machine->images = '["/img/haoyizu.png"]';
        if(empty($machine->brand))
            $machine->brand = "wsz";
        if(empty($machine->cover))
            $machine->cover = '/img/haoyizu.png';
       return $machine->save();
    }
}
