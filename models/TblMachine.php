<?php

namespace app\models;

use app\models\config\ConfigScheme;
use Yii;


class TblMachine extends \yii\db\ActiveRecord
{
    /*
     * status = 11 , 表示删除状态
     */
    // 添加数量，支持批量插入
    public $amount;

    /*
     * 查询机器维修状态
     */
    public function getMachineFault()
    {
        return $this->hasOne(TblMachineService::className(),['machine_id' => 'id']);
    }

    public static function tableName()
    {
        return 'tbl_machine';
    }

    public function rules()
    {
        return [
            [['model_id', 'images', 'brand'], 'required'],
            ['amount','required','on'=>['create']],
            [['wx_id', 'model_id', 'status', 'maintain_count', 'rent_count', 'add_time', 'come_from','amount'], 'integer'],
            [['buy_price'], 'number'],
            [['buy_date'], 'safe'],
            [['model_name', 'brand', 'series_id'], 'string', 'max' => 50],
            [['brand_name', 'cover'], 'string', 'max' => 100],
            [['images', 'remark'], 'string', 'max' => 500],
        ];
    }

    public function beforeSave($insert)
    {
        $this->cover = str_replace('/s/','/m/',json_decode($this->images,true)[0]);
        $this->brand_name = ConfigScheme::brand($this->brand);
        $this->updateModel();
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
            'id' => '机器编号',
            'wx_id' => '公众号id',
            'model_id' => '选择机型',
            'model_name' => '机型',
            'brand' => '品牌字母',
            'brand_name' => '品牌名字',
            'series_id' => '客户编号',
            'buy_price' => '购买价格',
            'buy_date' => '购买时间',
            'cover' => '封面图片',
            'images' => '机器图片',
            'remark' => '机器备注',
            'status' => '状态',
            'maintain_count' => '维修次数',
            'rent_count' => '租借次数',
            'add_time' => '添加时间',
            'come_from' => '机器分类',
            'amount' => '数量',
        ];
    }


    /*
     * 批量插入机器
     * ['wx_id','model_id','series_id','buy_date','buy_price','else_attr','add_time']
     */
    public function multiSave()
    {
        $this->cover = str_replace('/s/','/m/',json_decode($this->images,true)[0]);
        $this->brand_name = ConfigScheme::brand($this->brand);
        $this->updateModel();                           // 更新 model_name

        $wx_id = Cache::getWid();
        $rows = [];
        for($i=0;$i< $this->amount;$i++){
            $row[0] = $wx_id;
            $row[1] = $this->model_id ;
            $row[2] = $this->brand;
            $row[3] = $this->brand_name;
            $row[4] = $this->cover;
            $row[5] = $this->images;
            $row[6] = $this->buy_date;
            $row[7] = $this->buy_price;
            $row[8] = time();
            $row[9] = $this->model_name;
            $row[10] = $this->remark;
            $row[11] = $this->series_id;
            $rows[] = $row;
        }

        $row = Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id','model_id','brand','brand_name','cover','images','buy_date','buy_price','add_time','model_name','remark','series_id'],$rows
        )->execute();

        return $row;
    }

    /*
     * 添加机器或者修改机器，获取机型名字
     */
    public function updateModel()
    {
        if( !isset($this->oldAttributes['model_id']) || $this->attributes['model_id'] != $this->oldAttributes['model_id']){
            $this->model_name = (new \yii\db\Query())
                ->select('model')
                ->from('tbl_machine_model')
                ->where(['id'=>$this->attributes['model_id']])
                ->scalar();
        }
    }
}
