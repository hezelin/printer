<?php

namespace app\models;

use Yii;
//use app\models\TblWeixin;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use karpoff\icrop\CropImageUploadBehavior;


class TblMachine extends \yii\db\ActiveRecord
{
    // 添加数量，支持批量插入
    public $amount;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_machine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'brand', 'type', 'buy_time', 'add_time'], 'required'],
            [['wx_id', 'add_time', 'rent_time', 'maintain_time', 'status'], 'integer'],
            [['price', 'monthly_rent'], 'number'],
            [['buy_time'], 'safe'],
            [['enable'], 'string'],
            [['serial_id'], 'string', 'max' => 10],
            [['brand', 'type'], 'string', 'max' => 50],
            [['cover'], 'string', 'max' => 60],
            [['function'], 'string', 'max' => 300],
            [['else_attr'], 'string', 'max' => 1000],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '系统id',
            'wx_id' => '公众号id',
            'serial_id' => '编号',
            'brand' => '品牌',
            'type' => '机器型号',
            'cover' => '封面',
            'price' => '成本价格',
            'monthly_rent' => '月租',
            'buy_time' => '上市时间',
            'add_time' => '添加时间',
            'rent_time' => '出租次数',
            'maintain_time' => '维修次数',
            'function' => '功能',
            'else_attr' => '补充属性',
            'status' => '机器状态',
            'enable' => '是否有效',
            'amount' => '机器数量',
        ];
    }

    /*
     * 更新 tbl_weixin 表上的  machine_count 计数
     * $type = add / dec 减少
     */
    public function updateCount($type='add',$num=1)
    {
        if( !in_array($type,['add','dec']))
            throw new InvalidParamException;

        $wx = TblWeixin::findOne( Yii::$app->session['wechat']['id'] );
        if($type === 'add')
            $wx->machine_count = $wx->machine_count + $num;
        else $wx->machine_count = $wx->machine_count - $num;

        Yii::$app->session['wechat.machine_count'] = $wx->machine_count;

        if( !$wx->save() )
            throw new NotFoundHttpException('计数错误');

        Yii::$app->session['wechat'] = $wx->attributes;
        return true;


    }


    /*
     * 批量插入机器
     * ['wx_id','serial_id','brand','type','price','buy_time','add_time']
     */
    public function multiSave()
    {

        $count = Yii::$app->session['wechat']['machine_count'] + 1;
        $rows = [];
        for($i=0;$i< $this->amount;$i++){
            $row[0] = Yii::$app->session['wechat']['id'];
            $row[1] = $this->serial_id . $count ++ ;
            $row[2] = $this->brand ;
            $row[3] = $this->type;
            $row[4] = $this->price;
            $row[5] = $this->buy_time;
            $row[6] = $this->monthly_rent;
            $row[7] = $this->function;
            $row[8] = $this->cover;
            $row[9] = $this->else_attr;
            $row[10] = time();
            $rows[] = $row;
        }

        $row = Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id','serial_id','brand','type','price','buy_time','monthly_rent','function','cover','else_attr','add_time'],$rows
        )->execute();

        if($row)
            $this->updateCount('add',$row);
        return $row;

    }
}
