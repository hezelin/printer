<?php

namespace app\models\code;

use app\models\Cache;
use Yii;

class MachineAhead
{
    private $_wid;
    private $_num;
    public  $seriesData = [];
    /*
     * 构建默认的数据
     */
    public function __construct($num)
    {
        $this->_wid = Cache::getWid();
        $this->_num = $num;
    }

    /*
     * 创建机器单元
     */
    public function addItems()
    {
        $items = [];
        $t = time();
        $date = date('Y-m-d',$t);

        for($i=$this->_num; $i>0; $i--){
            $tmpSeries = $this->getSeriesId();
            $this->seriesData[] = $tmpSeries;
            $items[] = [$this->_wid,0,$tmpSeries,$date,0,4,$t];
        }
        return $items;
    }

    /*
     * 创建租赁资料单元
     */
    public function addApply()
    {
        $items = [];
        $due_time = strtotime('10 year',time());
        foreach($this->getMachineIdBySeries() as $m){
            $tmpSeries = $this->getSeriesId();
            $this->seriesData[] = $tmpSeries;
            $items[] = ['未设置','未设置',0,$this->_wid,$m['id'],$m['series_id'],$due_time,$due_time,3,2];
        }
        return $items;
    }

    /*
     * 获取机器id /机器系列号
     */
    public function getMachineIdBySeries()
    {
        $data = (new \yii\db\Query())
            ->select('id,series_id')
            ->from('tbl_machine')
            ->where(['wx_id'=>$this->_wid,'series_id'=>$this->seriesData])
            ->all();
        return $data;
    }

    /*
     * 保存参数
     */
    public function createAll()
    {
        $row = Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id', 'model_id','series_id','buy_date','buy_price','come_from','add_time'],$this->addItems())->execute();

        if($row){
            Yii::$app->db->createCommand()->batchInsert('tbl_rent_apply',
                ['phone', 'name', 'project_id','wx_id','machine_id', 'openid','due_time','first_rent_time','rent_period','status'],$this->addApply())->execute();
        }
    }

    /*
     * 获取系列号
     * redis 缓存，全局 machine.ahead.count
     */
    public function getSeriesId()
    {
        if( !$num = Yii::$app->cache->get('machine.ahead.count') ){
            $num = time();
            Yii::$app->cache->set('machine.ahead.count',$num);
        }
        Yii::$app->cache->set('machine.ahead.count',$num+1);
        return 'A'.$num;
    }
}
