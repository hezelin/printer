<?php

namespace app\models;

use Yii;

class TblMachineRentProject extends \yii\db\ActiveRecord
{
    public $brand;

    public static function tableName()
    {
        return 'tbl_machine_rent_project';
    }

    public function rules()
    {
        return [
            [['brand', 'machine_model_id', 'lowest_expense', 'black_white', 'images'], 'required'],
            [['wx_id', 'machine_model_id', 'contain_paper', 'is_show', 'add_time'], 'integer'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['describe'], 'string'],
            [['cover'], 'string', 'max' => 100],
            [['images'], 'string', 'max' => 500],
        ];
    }

    public function beforeSave($insert)
    {
        $this->cover = str_replace('/s/','/m/',json_decode($this->images,true)[0]);
        if (parent::beforeSave($insert)) {
            if($insert)
            {
                $this->wx_id = $this->wx_id? :Cache::getWid();
                $this->add_time = time();
            }  // 新增加数据，保存用户id
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_id' => '公众号id',
            'machine_model_id' => '机型',
            'lowest_expense' => '最低消费',
            'contain_paper' => '包含纸张',
            'black_white' => '黑白价格',
            'colours' => '彩色价格',
            'cover' => '方案图片',
            'images' => '方案图片',
            'is_show' => '是否显示',
            'describe' => '方案描述',
            'add_time' => '添加时间',
            'brand' => '品牌',
        ];
    }
}
