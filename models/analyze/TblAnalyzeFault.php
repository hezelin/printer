<?php

namespace app\models\analyze;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Cache;

class TblAnalyzeFault
{
    public $startTime;
    public $endTime;

    public $error = [];
    public $delCmd = false;                 // 是否执行删除命令
    public $status = true;
    /*
     * 从数据库 获取数据
     */
    public function addData()
    {
        $data = (new \yii\db\Query())
            ->select('weixin_id as wx_id,count(id) as add_count')
            ->from('tbl_machine_service')
            ->where(['enable'=>'Y'])
            ->andWhere(['between','add_time',$this->startTime,$this->endTime])
            ->groupBy('wx_id')
            ->all();
        return $data;
    }
    /*
     * 获取 所有的维修数量
     */
    public function allData()
    {
        $data = (new \yii\db\Query())
            ->select('weixin_id as wx_id,count(id) as total_count')
            ->from('tbl_machine_service')
            ->where(['enable'=>'Y'])
            ->andWhere(['<','add_time',$this->endTime])
            ->groupBy('wx_id')
            ->all();
        if($data){
            $tmp = [];
            foreach($data as $d){
                $tmp[$d['wx_id']]['total_count'] = $d['total_count'];
            }
            return $tmp;
        }
        return $data;
    }
    /*
     * 获取 某天删除的数据
     */
    public function delData()
    {
        $data = (new \yii\db\Query())
            ->select('weixin_id as wx_id,count(id) as cancel_count')
            ->from('tbl_machine_service')
            ->where(['enable'=>'N'])
            ->andWhere(['between','opera_time',$this->startTime,$this->endTime])
            ->groupBy('wx_id')
            ->all();
        return $data;
    }

    /*
     * 处理数据为 sql,准备入库
     */
    public function addSql()
    {
        if( !$all = $this->allData() ) return false;

        foreach($this->addData() as $d)
            $all[$d['wx_id']]['add_count'] = $d['add_count'];

        foreach($this->delData() as $d)
            $all[$d['wx_id']]['cancel_count'] = $d['cancel_count'];

        $str='';
        foreach($all as $wxId=>$d){
            $addCount = isset($d['add_count'])? $d['add_count']:0;
            $cancelCount = isset($d['cancel_count'])? $d['cancel_count']:0;
            $totalCount = isset($d['total_count'])? $d['total_count']:0;
            $str .= ",({$this->startTime},{$wxId},{$addCount},{$cancelCount},{$totalCount})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_fault (date_time,wx_id,add_count,cancel_count,total_count) values {$str}
          ON DUPLICATE KEY UPDATE add_count=values(add_count),cancel_count=values(cancel_count),total_count=values(total_count); ";
    }


    /*
     * 处理 删除的 sql,准备更新数据库
     * 重复执行？
     */
    public function delSql()
    {
        if( !$data = $this->delData() ) return false;

        $str='';
        foreach($data as $d){
            $addTime = strtotime(date('Y-m-d',$d['add_time']));
            $str .= ",({$addTime},{$d['wx_id']},{$d['cancel_count']})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_fault (date_time,wx_id,cancel_count) values {$str}
          ON DUPLICATE KEY UPDATE cancel_count=cancel_count-values(cancel_count); ";

    }
    /*
     * 入库处理
     */
    public function saveSql()
    {
        if( $sql = $this->addSql() )
            Yii::$app->db->createCommand($sql)->execute();
        if( $this->delCmd && ($del = $this->delSql()) )
            Yii::$app->db->createCommand($del)->execute();

        return $this->status;
    }

    /*
     * 执行当天的统计,当天临时 到  即时时间
     */
    public function today($delCmd = false)
    {
        $this->delCmd = $delCmd;
        $this->startTime = strtotime(date('Y-m-d',time()));
        $this->endTime = time() - 1;
        return $this->saveSql();
    }

    /*
     * 执行昨天的统计，crontab 跑这个
     */
    public function yesterday($delCmd = false)
    {
        $this->delCmd = $delCmd;
        $this->startTime = strtotime(date('Y-m-d',strtotime('-1 day')));
        $this->endTime = strtotime(date('Y-m-d',time())) - 1;
        return $this->saveSql();
    }

    /*
     * 更新历史数据,
     * $start，$end 为 负数，例如  -3，0
     * 0 表示当天，-3 表示前3天
     */
    public function historyDay($start,$end,$delCmd = false)
    {
        if($start > $end) return false;
        $this->delCmd = $delCmd;
        for($i=$start;$i <= $end;$i++){
            $this->startTime = strtotime(date('Y-m-d',strtotime($i.' day')));
            $this->endTime = strtotime(date('Y-m-d',strtotime(($i+1).' day'))) - 1;
            if( $this->saveSql() === false )
                $this->error[] = date('Y-m-d',strtotime($i.' day'));
        }
        return $this->status;
    }

    /*
     * 返回 图表的数据,每天编号
     */
    public function getCharts()
    {
        if(Yii::$app->request->get('start') && Yii::$app->request->get('end') ){
            $start = strtotime( Yii::$app->request->get('start') );
            $end = strtotime( Yii::$app->request->get('end') );
        }else{
            $start = strtotime(date('Y-m-d',strtotime('-10 day')));
            $end = strtotime(date('Y-m-d',strtotime('-1 day')));
        }

        $data = (new \yii\db\Query())
            ->select('date_time,add_count,cancel_count,total_count')
            ->from('tbl_analyze_fault')
            ->where(['between','date_time',$start,$end])
            ->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->all();
        $chart['start'] = date('Y-m-d',$start);
        $chart['end'] = date('Y-m-d',$end);
        $tmp = [];
        if($data){
            foreach($data as $d){
                $chart['cate'][] = date('Y-m-d',$d['date_time']);
                $tmp['add'][] = (int)$d['add_count'];
                $tmp['cancel'][] = (int)$d['cancel_count'];
                $tmp['total'][] = (int)$d['total_count'];
            }
        }else
            $chart['cate'] = [];

        $chart['series'] = [
            [
                'name'=>'累计维修',
                'data'=> isset($tmp['total'])? $tmp['total']:[]
            ],[
                'name'=>'新增维修',
                'data'=> isset($tmp['add'])? $tmp['add']:[]
            ],[
                'name'=>'取消维修',
                'data'=> isset($tmp['cancel'])? $tmp['cancel']:[]
            ],
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }
}
