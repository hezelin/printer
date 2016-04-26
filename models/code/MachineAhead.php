<?php

namespace app\models\code;

use app\models\Cache;
use Yii;

class MachineAhead
{
    private $_wid;
    private $_num;
    /*
     * 构建默认的数据
     */
    public function __construct($num)
    {
        $this->_wid = Cache::getWid();
        $this->_num = $num;
    }

    /*
     * 创建
     */
    public function addItems()
    {
        $items = [];
        $t = time();
        $date = date('Y-m-d',$t);

        for($i=$this->_num; $i>0; $i--){
            $items[] = [$this->_wid,0,$this->getSeriesId(),$date,0,4,$t];
        }
        return $items;
    }

    /*
     * 保存参数
     */
    public function createAll()
    {
        return Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id', 'model_id','series_id','buy_date','buy_price','come_from','add_time'],$this->addItems())->execute();


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
