<?php

namespace app\models\analyze;

use app\models\Cache;
use Yii;
use yii\helpers\ArrayHelper;

class TblAnalyzeRent
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
        $all = (new \yii\db\Query())
            ->select('wx_id,count(id) as total_count')
            ->from('tbl_rent_apply')
            ->where(['status'=>'2'])
            ->andWhere(['<','add_time',$this->endTime])
            ->groupBy('wx_id')
            ->all();

        $add = (new \yii\db\Query())
            ->select('wx_id,count(id) as add_count')
            ->from('tbl_rent_apply')
            ->where(['<','status',11])
            ->andWhere(['between','add_time',$this->startTime,$this->endTime])
            ->groupBy('wx_id')
            ->all();

        $expire = (new \yii\db\Query())
                ->select('wx_id,count(id) as expire_count')
                ->from('tbl_rent_apply')
                ->where(['<','status',11])
                ->andWhere(['<','due_time',$this->endTime+86400*7])
                ->groupBy('wx_id')
                ->all();
        $collect = (new \yii\db\Query())
            ->select('wx_id,count(id) as collect_count')
            ->from('tbl_rent_apply')
            ->where(['<','status',11])
            ->andWhere(['<','first_rent_time',$this->endTime+86400*3])
            ->groupBy('wx_id')
            ->all();
        $tmp = [];
        if($all){
            foreach($all as $d){
                $tmp[$d['wx_id']]['total_count'] = $d['total_count'];
            }
        }
        if($add){
            foreach($add as $d){
                $tmp[$d['wx_id']]['add_count'] = $d['add_count'];
            }
        }
        if($expire){
            foreach($expire as $d){
                $tmp[$d['wx_id']]['expire_count'] = $d['expire_count'];
            }
        }
        if($collect){
            foreach($collect as $d){
                $tmp[$d['wx_id']]['collect_count'] = $d['collect_count'];
            }
        }
        unset($data);
        return $tmp;
    }


    /*
     * 处理数据为 sql,准备入库
     */
    public function addSql()
    {
        if( !$data = $this->addData() ) return false;
        $str='';
        foreach($data as $wid=>$d){
            $add = isset($d['add_count'])? $d['add_count']:0;
            $expire = isset($d['expire_count'])? $d['expire_count']:0;
            $collect = isset($d['collect_count'])? $d['collect_count']:0;
            $total = isset($d['total_count'])? $d['total_count']:0;
            $str .= ",({$this->startTime},{$wid},{$total},{$add},{$expire},{$collect})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_rent (date_time,wx_id,total_count,add_count,expire_count,collect_count) values {$str}
          ON DUPLICATE KEY UPDATE total_count=values(total_count),add_count=values(add_count),expire_count=values(expire_count),collect_count=values(collect_count); ";
    }


    /*
     * 入库处理
     */
    public function saveSql()
    {
        if( $sql = $this->addSql() )
            Yii::$app->db->createCommand($sql)->execute();
        return $this->status;
    }

    /*
     * 执行当天的统计,当天临时 到  即时时间
     */
    public function today()
    {
        $this->startTime = strtotime(date('Y-m-d',time()));
        $this->endTime = time() - 1;
        return $this->saveSql();
    }

    /*
     * 执行昨天的统计，crontab 跑这个
     */
    public function yesterday()
    {
        $this->startTime = strtotime(date('Y-m-d',strtotime('-1 day')));
        $this->endTime = strtotime(date('Y-m-d',time())) - 1;
        return $this->saveSql();
    }

    /*
     * 更新历史数据,
     * $start，$end 为 负数，例如  -3，0
     * 0 表示当天，-3 表示前3天
     */
    public function historyDay($start,$end)
    {
        if($start > $end) return false;
        for($i=$start;$i <= $end;$i++){
            $this->startTime = strtotime(date('Y-m-d',strtotime($i.' day')));
            $this->endTime = strtotime(date('Y-m-d',strtotime(($i+1).' day'))) - 1;
            if( $this->saveSql() === false )
                $this->error[] = date('Y-m-d',strtotime($i.' day'));
        }
        return $this->status;
    }

    /*
     * 返回 图表的数据
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
            ->select('date_time,total_count,add_count,collect_count,expire_count')
            ->from('tbl_analyze_rent')
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
                $tmp['collect'][] = (int)$d['collect_count'];
                $tmp['expire'][] = (int)$d['expire_count'];
                $tmp['total'][] = (int)$d['total_count'];
            }
        }else
            $chart['cate'] = [];

        $chart['series'] = [
            [
                'name'=>'快过期',
                'type'=>'column',
                'yAxis'=>1,
                'data'=> isset($tmp['expire'])? $tmp['expire']:[]
            ],[
                'name'=>'新增租借',
                'type'=>'column',
                'yAxis'=>1,
                'data'=> isset($tmp['add'])? $tmp['add']:[]
            ],[
                'name'=>'待收租',
                'type'=>'column',
                'yAxis'=>1,
                'data'=> isset($tmp['collect'])? $tmp['collect']:[]
            ],[
                'name'=>'累计租借',
                'type'=>'spline',
                'data'=> isset($tmp['total'])? $tmp['total']:[]
            ],
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }
}
