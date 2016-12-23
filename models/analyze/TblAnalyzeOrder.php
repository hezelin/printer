<?php

namespace app\models\analyze;

use app\models\Cache;
use Yii;
use yii\helpers\ArrayHelper;

class TblAnalyzeOrder
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
            ->select('wx_id,count(order_id) as total_num,sum(total_price) as total_money,sum(pay_score) as total_score')
            ->from('tbl_shop_order')
            ->where(['enable'=>'Y'])
            ->andWhere(['<','add_time',$this->endTime])
            ->andWhere(['<','order_status',8])
            ->groupBy('wx_id')
            ->all();

        $add = (new \yii\db\Query())
            ->select('wx_id,count(order_id) as new_num,sum(total_price) as new_money,sum(pay_score) as new_score')
            ->from('tbl_shop_order')
            ->where(['enable'=>'Y'])
            ->andWhere(['between','add_time',$this->startTime,$this->endTime])
            ->andWhere(['<','order_status',8])
            ->groupBy('wx_id')
            ->all();
        $tmp = [];
        if($all){
            foreach($all as $d){
                $tmp[$d['wx_id']]['total_num'] = $d['total_num'];
                $tmp[$d['wx_id']]['total_money'] = $d['total_money'];
                $tmp[$d['wx_id']]['total_score'] = $d['total_score'];
            }
        }
        if($add){
            foreach($add as $d){
                $tmp[$d['wx_id']]['new_num'] = $d['new_num'];
                $tmp[$d['wx_id']]['new_money'] = $d['new_money'];
                $tmp[$d['wx_id']]['new_score'] = $d['new_score'];
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
            $newNum = isset($d['new_num'])? $d['new_num']:0;
            $newMoney = isset($d['new_money'])? $d['new_money']:0;
            $newScore = isset($d['new_score'])? $d['new_score']:0;
            $talNum = isset($d['total_num'])? $d['total_num']:0;
            $talMoney = isset($d['total_money'])? $d['total_money']:0;
            $talScore = isset($d['total_score'])? $d['total_score']:0;
            $str .= ",({$this->startTime},{$wid},{$newNum},{$newMoney},{$newScore},{$talNum},{$talMoney},{$talScore})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_order (date_time,wx_id,new_num,new_money,new_score,total_num,total_money,total_score) values {$str}
          ON DUPLICATE KEY UPDATE new_num=values(new_num),new_money=values(new_money),new_score=values(new_score),total_num=values(total_num),total_money=values(total_money),total_score=values(total_score); ";
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
            //->select('date_time,total_num,total_money,total_score,new_money,new_score,new_num')
            ->select('date_time,total_num,total_money,new_money,new_num')//20161223 修改
            ->from('tbl_analyze_order')
            ->where(['between','date_time',$start,$end])
            ->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->all();

        $chart['start'] = date('Y-m-d',$start);
        $chart['end'] = date('Y-m-d',$end);
        $tmp = $new =[];
        if($data){
            foreach($data as $d){
                $chart['cate'][] = date('Y-m-d',$d['date_time']);
                $tmp['money'][] = (float)$d['total_money'];
                //$tmp['score'][] = (int)$d['total_score'];
                $tmp['num'][] = (int)$d['total_num'];
                $new['newMoney'][] = (float)$d['new_money'];
                //$new['newScore'][] = (int)$d['new_score'];
                $new['newNum'][] = (int)$d['new_num'];
            }
        }else
            $chart['cate'] = [];

        $chart['series'] = [
            [
                'name'=>'累计金额',
                'type'=>'column',
                'yAxis'=>1,
                'tooltip'=>['valueSuffix'=>'元'],
                'data'=> isset($tmp['money'])? $tmp['money']:[]
            ],
            [
                'name'=>'累计数量',
                'type'=>'spline',
                'yAxis'=>0,//20161223 修改
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($tmp['num'])? $tmp['num']:[]
            ]/*,[
                'name'=>'累计积分',
                'type'=>'spline',
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($tmp['score'])? $tmp['score']:[]
            ]*/
        ];

        $chart['day'] = [
            [
                'name'=>'新增数量',
                'type'=>'column',
                'yAxis'=>0,//20161223 修改
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($new['newNum'])? $new['newNum']:[]
            ],
            [
                'name'=>'新增金额',
                'type'=>'spline',
                'yAxis'=>1,//20161223 修改
                'tooltip'=>['valueSuffix'=>'元'],
                'data'=> isset($new['newMoney'])? $new['newMoney']:[]
            ]/*,[
                'name'=>'新增积分',
                'type'=>'spline',
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($new['newScore'])? $new['newScore']:[]
            ]*///20161223 修改
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }
}
