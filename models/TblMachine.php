<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;

class TblMachine extends \yii\db\ActiveRecord
{
    // 添加数量，支持批量插入
    public $amount;
    public $seriesLists = [];

    /*
     * 关联机器模型
     */
    public function getMachineModel()
    {
        return $this->hasOne(TblMachineModel::className(), ['id' => 'model_id']);
    }

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
            [['wx_id', 'model_id', 'series_id', 'buy_date', 'buy_price', 'add_time'], 'required'],
            [['wx_id', 'model_id', 'status', 'maintain_count', 'rent_count', 'come_from', 'add_time'], 'integer'],
            [['buy_date'], 'safe'],
            [['buy_price','amount'], 'number'],
            [['amount'],'default','value'=>1],
            [['enable'], 'string'],
            [['else_attr'], 'string', 'max' => 1000],
            [['else_attr'], 'default', 'value' => '[]'],
            [['series_id'],'checkNum'],
            [['wx_id', 'series_id'], 'unique', 'targetAttribute' => ['wx_id', 'series_id'], 'message' => '编号已经存在.']
        ];
    }

    /*
     * 自定义验证规则，验证编号 series_id 跟 添加机器数量是否对应
     * 遍历每个series_id 的长度，超过40验证失败
     */
    public function checkNum($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->amount >1){
                $this->seriesLists = array_filter(explode(',',str_replace(['，',' '],[','],$this->series_id) ) );
                if(count($this->seriesLists) != (int)$this->amount)
                    $this->addError($attribute, '系列号必须填写 '.$this->amount . '个，并且用逗号,隔开');
                if(!$this->hasErrors())
                    foreach($this->seriesLists as $one){
                        if( strlen($one) > 30){
                            $this->addError($attribute, '系列号 '.$one.' 长度不能大于30个字符');
                            break;
                        }
                    }
            }else if( strlen($this->amount) >30 )
                $this->addError($attribute, '系列号长度不能大于30个字符');
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => '系统id',
            'wx_id' => '公众号id',
            'model_id' => '选择机型',
            'series_id' => '机身序列号',
            'buy_date' => '购买日期',
            'buy_price' => '购买价格',
            'else_attr' => '补充属性',
            'status' => '状态',
            'maintain_count' => '维修次数',
            'rent_count' => '租借次数',
            'come_from' => '自家机器',
            'add_time' => '添加时间',
            'enable' => '是否有效',
            'amount' => '机器数量',
        ];
    }

    /*
     * 更新 tbl_machine_model 表上的  machine_count 计数
     * $type = add / dec 减少
     */
    public function updateCount($type='add',$num=1)
    {
        if( !in_array($type,['add','dec']))
            throw new InvalidParamException;

        $model = TblMachineModel::findOne($this->model_id );
        if($type === 'add')
            $model->machine_count = $model->machine_count + $num;
        else $model->machine_count = $model->machine_count - $num;

        if( !$model->save() )
            throw new NotFoundHttpException('计数错误');
    }


    /*
     * 批量插入机器
     * ['wx_id','model_id','series_id','buy_date','buy_price','else_attr','add_time']
     */
    public function multiSave()
    {
//        判断系列号是否存在
        $data = (new \yii\db\Query())
            ->select('series_id')
            ->from('tbl_machine')
            ->where(['in','series_id',$this->seriesLists])
            ->all();
        if($data){
            Yii::$app->session->setFlash('error','系列号“'.ToolBase::arrayToString($data).'” 已经存在。');
            return false;
        }

        $wx_id = Cache::getWid();
        $rows = [];
        for($i=0;$i< $this->amount;$i++){
            $row[0] = $wx_id;
            $row[1] = $this->model_id ;
            $row[2] = $this->seriesLists[$i];
            $row[3] = $this->buy_date;
            $row[4] = $this->buy_price;
            $row[5] = $this->else_attr;
            $row[6] = time();
            $rows[] = $row;
        }

        $row = Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id','model_id','series_id','buy_date','buy_price','else_attr','add_time'],$rows
        )->execute();

        if($row)
            $this->updateCount('add',$row);
        return $row;
    }
}
