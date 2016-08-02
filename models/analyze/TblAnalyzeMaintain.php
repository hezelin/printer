<?php

namespace app\models\analyze;

use app\models\Cache;
use Yii;
use yii\helpers\ArrayHelper;

class TblAnalyzeMaintain
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
            ->select('weixin_id,openid,sum(fault_time) as fault_time,sum(resp_time) as resp_time,sum(resp_km) as total_km,count(id) as total_fault,sum(fault_score) as total_score')
            ->from('tbl_machine_service')
            ->where(['<','status',11])
            ->andWhere(['>','status',7])
            ->andWhere(['between','add_time',$this->startTime,$this->endTime])
            ->groupBy('weixin_id,openid')
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
            $str .= ",({$date},{$d['weixin_id']},'{$d['openid']}',{$d['fault_time']},{$d['resp_time']},{$d['total_km']},{$d['total_fault']},{$d['total_score']})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_maintain (date,wx_id,openid,fault_time,resp_time,total_km,total_fault,total_score) values {$str}
          ON DUPLICATE KEY UPDATE fault_time=values(fault_time),resp_time=values(resp_time),total_km=values(total_km),total_fault=values(total_fault),total_score=values(total_score); ";
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
        $this->startTime = strtotime(date('Y-m',$this->endTime));
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
     * 返回 图表的数据
     */
    public function getCharts()
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

        $line = Yii::$app->request->get('line')? : 'fault_count';

        if( !in_array($line,$this->getParam()))
            exit('出错');

        $data = (new \yii\db\Query())
            ->select('date,p.name,t.openid,'.$this->getParam($line).' as val,')
            ->from('tbl_analyze_maintain t')
            ->leftJoin('tbl_user_maintain p','p.openid=t.openid')
            ->where(['between','date',$start,$end])
            ->andWhere('t.wx_id=:wid',[':wid'=>Cache::getWid()])
            ->orderBy('date asc')
            ->all();

        $names = $this->getName($data);

        $chart['start'] = date('Y-m-d',strtotime($start.$sx));
        $chart['end'] = date('Y-m-d',strtotime($end.$ex));
        $chart['line'] = $line;
        $blank = [];
        $chart['cate'] = $this->getCate($start,$end);
        $chart['tips'] = $this->getTips($line);

        foreach($chart['cate'] as $d)
            $blank[$d] = 0;

        $chart['series'] = [];
        if($data){
            foreach ($names as $k=>$n) {
                $tmp = [];
                $tmp['name'] = $n;
                $tmp['data'] = $blank;
                foreach($data as $d){
                    if($d['name'] == $n)
                        $tmp['data'][$d['date']] = (float)$d['val'];
                }
                $tmp['data'] = array_values($tmp['data']);
                $chart['series'][] = $tmp;
            }


        }
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

    /*
     * 返回维修员姓名，去掉重复值
     */
    private function getName($data)
    {
        $arr = [];
        foreach($data as $d)
        {
            $arr[$d['name']] = '';
        }
        return array_keys($arr);
    }

    private function getParam($key='')
    {
        $data = [
            'fault_count'=>'total_fault',
            'resp'=>'(total_km/resp_time*1000)',
            'time'=>'(fault_time/60)',
            'score'=>'(total_score/total_fault)',
        ];
        return $key? $data[$key]:array_keys($data);
    }

    private function getTips($key)
    {
        $data = [
            'fault_count'=>['title'=>'维修员绩效（维修次数）','y'=>'数量'],
            'resp'=>['title'=>'维修员绩效 -- 反应速度（米/秒）','y'=>'米/秒'],
            'time'=>['title'=>'维修员绩效 -- 维修时间（分钟）','y'=>'时间（分）'],
            'score'=>['title'=>'维修员绩效 -- 评价平均分','y'=>'分数'],
        ];
        return $data[$key];
    }
}
