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
        for($i=$this->_num; $i>0; $i--){
            $items[] = [$this->_wid,0,4,$t];
        }
        return $items;
    }

    /*
     * 保存参数
     */
    public function createAll()
    {
        $row = Yii::$app->db->createCommand()->batchInsert('tbl_machine',
            ['wx_id', 'model_id','come_from','add_time'],$this->addItems())->execute();
    }
}
