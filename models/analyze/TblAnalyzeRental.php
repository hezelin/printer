<?php

namespace app\models\analyze;
use app\models\Cache;
use Yii;
use yii\helpers\ArrayHelper;

class TblAnalyzeRental
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
            ->select('wx_id,sum(total_money) as total_money,sum(exceed_money) as exceed_money')
            ->from('tbl_rent_report')
            ->where(['enable'=>'Y'])
            ->andWhere(['between','add_time',$this->startTime,$this->endTime])
            ->groupBy('wx_id')
            ->all();
        return $data;
    }


    /*
     * 处理数据为 sql,准备入库
     */
    public function addSql()
    {
        if( !$data = $this->addData() ) return false;

        $date = date('Ym',$this->startTime);
        $str='';
        foreach($data as $d){
            $str .= ",({$date},{$d['wx_id']},'{$d['total_money']}',{$d['exceed_money']})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_rental (date,wx_id,total_money,exceed_money) values {$str}
          ON DUPLICATE KEY UPDATE exceed_money=values(exceed_money),total_money=values(total_money); ";
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
        $this->startTime = strtotime(date('Y-m',time()));
        $this->endTime = time() - 1;
        return $this->saveSql();
    }

    /*
     * 执行昨天的统计，crontab 跑这个
     */
    public function yesterday()
    {
        $this->endTime = strtotime(date('Y-m',time())) - 1;
        $this->startTime = $this->endTime - 86399;
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
            $this->startTime = strtotime(date('Y-m-d',strtotime($i.' month')));
            $this->endTime = strtotime(date('Y-m-d',strtotime(($i+1).' month'))) - 1;
            if( $this->saveSql() === false )
                $this->error[] = date('Y-m-d',strtotime($i.' day'));
        }
        return $this->status;
    }


    /*
     * 返回 单个机器的图表数据
     */
    public function getCharts()
    {
        if(Yii::$app->request->get('start') && Yii::$app->request->get('end') ){
            $start = strtotime( Yii::$app->request->get('start') );
            $end = strtotime( Yii::$app->request->get('end') );
        }else{
            $start = strtotime('-1 year');
            $end = time();
        }

        $data = (new \yii\db\Query())
            ->select('add_time,total_money,exceed_money')
            ->from('tbl_rent_report')
            ->where(['between','add_time',$start,$end])
            ->andWhere('wx_id=:wid and machine_id=:mid',[':wid'=>Cache::getWid(),':mid'=>Yii::$app->request->get('machine_id')])
            ->all();
        $chart['start'] = date('Y-m-d',$start);
        $chart['end'] = date('Y-m-d',$end);
        $tmp = [];
        if($data){
            foreach($data as $d){
                $chart['cate'][] = date('Y-m-d',$d['add_time']);
                $tmp['total'][] = (int)$d['total_money'];
                $tmp['exceed'][] = (int)$d['exceed_money'];
            }
        }else
            $chart['cate'] = [];

        $chart['series'] = [
            [
                'name'=>'租金',
                'type'=>'column',
                'data'=> isset($tmp['total'])? $tmp['total']:[]
            ],[
                'name'=>'超出金额',
                'type'=>'column',
                'color'=>'rgb(144, 237, 125)',
                'data'=> isset($tmp['exceed'])? $tmp['exceed']:[]
            ]
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }

    /*
     * 返回收租图表/ 按月
     */
    /*
     * 返回 图表的数据
     */
    public function getRentalCharts()
    {
        if(Yii::$app->request->get('start') && Yii::$app->request->get('end') ){
            $start = substr(Yii::$app->request->get('start'),0,6);
            $end = substr(Yii::$app->request->get('end'),0,6);
            $sx = substr(Yii::$app->request->get('start'),6);
            $ex = substr(Yii::$app->request->get('end'),6);
            if($start > $end)
                list($start,$end) = [$end,$start];
        }else{
            $start = date('Ym',strtotime('-10 month'));
            $end = date('Ym',time());
            $sx=$ex='01';
        }

        $data = (new \yii\db\Query())
            ->select('date,total_money,exceed_money')
            ->from('tbl_analyze_rental')
            ->where(['between','date',$start,$end])
            ->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->orderBy('date asc')
            ->all();

        $chart['start'] = date('Y-m-d',strtotime($start.$sx));
        $chart['end'] = date('Y-m-d',strtotime($end.$ex));
        $chart['cate'] = $this->getCate($start,$end);

        $tmp = [[],[]];
        if($data){
            $data = ArrayHelper::index($data,'date');
            foreach($chart['cate'] as $d)
            {
                if( isset($data[$d]) ){
                    $tmp[0][] = (float)$data[$d]['total_money'];
                    $tmp[1][] = (float)$data[$d]['exceed_money'];
                }else{
                    $tmp[0][] = 0;
                    $tmp[1][] = 0;
                }
            }
        }
        $chart['series'] = [
            [
                'name'=>'总租金',
                'data'=> $tmp[0]
            ],[
                'name'=>'超出租金',
                'color'=>'rgb(237, 144, 11)',
                'data'=> $tmp[1]
            ]
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }

    /*
     * 返回时间范围的日期
     * 201101,201511 ,$d = 4
     */
    private function getCate($s,$e)
    {
        $y1 = substr($s,0,4);
        $y2 = substr($e,0,4);
        if($d = ( $y2 - $y1 )){
            $cate = [];
            for($i=1;$i<=$d;$i++){
                $cate = array_merge($cate,range($s,$y1.'12'));
                $y1++;
                $s = $y1.'01';
            }
            return array_merge($cate,range($s,$e));
        }else
            return range($s,$e);
    }
}
