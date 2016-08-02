<?php

namespace app\models\analyze;

use app\models\Cache;
use Yii;
use yii\helpers\ArrayHelper;

class TblAnalyzeMachine
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
            ->select('wx_id,status,count(id) as machine_count')
            ->from('tbl_machine')
            ->where(['<','status',11])
            ->andWhere(['<','add_time',$this->endTime])
            ->groupBy('wx_id,status')
            ->all();
        $tmp = [];
        if($data){
            foreach($data as $d){
                if($d['status'] == 1)
                    $tmp[ $d['wx_id'] ]['free_count'] = $d['machine_count'];
                elseif($d['status'] == 2 )
                    $tmp[ $d['wx_id'] ]['rent_count'] = $d['machine_count'];
                else
                    $tmp[ $d['wx_id'] ]['scrap_count'] = $d['machine_count'];
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
            $free = isset($d['free_count'])? $d['free_count']:0;
            $rent = isset($d['rent_count'])? $d['rent_count']:0;
            $scrap = isset($d['scrap_count'])? $d['scrap_count']:0;
            $str .= ",({$this->startTime},{$wid},{$rent},{$free},{$scrap})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_machine (date_time,wx_id,rent_count,free_count,scrap_count) values {$str}
          ON DUPLICATE KEY UPDATE rent_count=values(rent_count),free_count=values(free_count),scrap_count=values(scrap_count); ";
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
        $initFrom = [
            1 => '出租',
            2 => '销售',
            3 => '维修',
            4 => '预设',
        ];

        $data = (new \yii\db\Query())
            ->select('count(*) as num,come_from')
            ->from('tbl_machine')
            ->where(['wx_id'=>Cache::getWid()])
            ->andWhere(['<','status',11])
            ->groupBy('come_from')
            ->all();

        $data = ArrayHelper::map($data,'come_from','num');
        arsort($data);                              // 根据值降排序
        $chart['total'] = array_sum($data);

        $tmp = [];
        foreach($data as $k=>$v)
        {
            $tmp[] = [
                'name' => $initFrom[$k],
                'y' => (int)$v,
            ];
            unset($initFrom[$k]);
        }
        if($chart['total'] && $initFrom)
            foreach($initFrom as $f)
                $tmp[] = [
                    'name' => $f,
                    'y' => 0,
                ];

        $chart['series'][] = [
            'name'=>'数量',
            'colorByPoint'=>true,
            'data' => $tmp
        ];

        $initStatus = [
            1 => '闲置中',
            2 => '已租借',
            3 => '已报废',
        ];

        $data = (new \yii\db\Query())
            ->select('count(*) as num,status')
            ->from('tbl_machine')
            ->where(['wx_id'=>Cache::getWid()])
            ->andWhere(['<','status',11])
            ->groupBy('status')
            ->all();

        $data = ArrayHelper::map($data,'status','num');
        arsort($data);                              // 根据值降排序
        $chart['total2'] = array_sum($data);

        $tmp = [];
        foreach($data as $k=>$v)
        {
            $tmp[] = [
                'name' => $initStatus[$k],
                'y' => (int)$v,
            ];
            unset($initStatus[$k]);
        }
        if($chart['total2'] && $initStatus)
            foreach($initStatus as $f)
                $tmp[] = [
                    'name' => $f,
                    'y' => 0,
                ];

        $chart['series2'][] = [
            'name'=>'数量',
            'colorByPoint'=>true,
            'data' => $tmp
        ];
        unset($data);
        unset($tmp);
        return $chart;
    }
}
