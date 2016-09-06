<?php

namespace app\models\analyze;

use app\models\Cache;
use Yii;

class TblAnalyzeProduct
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
            ->select('wx_id,sum(amount) as item_count,count(id) as cate_count,sum(market_price*amount) as cost_price,sum(price*amount) as sell_price')
            ->from('tbl_product')
            ->where(['enable'=>'Y'])
            ->andWhere(['<','add_time',$this->endTime])
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

        $str='';
        foreach($data as $d){
            $str .= ",({$this->startTime},{$d['wx_id']},{$d['item_count']},{$d['cate_count']},{$d['cost_price']},{$d['sell_price']})";
        }
        $str = substr($str,1);
        return "insert into tbl_analyze_product (date_time,wx_id,item_count,cate_count,cost_price,sell_price) values {$str}
          ON DUPLICATE KEY UPDATE item_count=values(item_count),cate_count=values(cate_count),cost_price=values(cost_price),sell_price=values(sell_price); ";
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
            ->select('date_time,cost_price,sell_price,item_count,cate_count')
            ->from('tbl_analyze_product')
            ->where(['between','date_time',$start,$end])
            ->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->all();
        $chart['start'] = date('Y-m-d',$start);
        $chart['end'] = date('Y-m-d',$end);
        $tmp = [];
        if($data){
            foreach($data as $d){
                $chart['cate'][] = date('md',$d['date_time']);
                $tmp['cost'][] = (float)$d['cost_price'];
                $tmp['sell'][] = (float)$d['sell_price'];
                $tmp['item'][] = (int)$d['item_count'];
            }
        }else
            $chart['cate'] = [];

        $chart['series'] = [
            [
                'name'=>'成本价',
                'type'=>'column',
                'yAxis'=>1,
                'tooltip'=>['valueSuffix'=>'元'],
                'data'=> isset($tmp['cost'])? $tmp['cost']:[]
            ],[
                'name'=>'预售价',
                'type'=>'column',
                'color'=>'rgb(144, 237, 125)',
                'yAxis'=>1,
                'tooltip'=>['valueSuffix'=>'元'],
                'data'=> isset($tmp['sell'])? $tmp['sell']:[],
            ],[
                'name'=>'商品数量',
                'type'=>'spline',
                'color'=>'rgb(255, 188, 117)',
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($tmp['item'])? $tmp['item']:[]
            ],/*[
                'name'=>'商品种类',
                'tooltip'=>['valueSuffix'=>'个'],
                'data'=> isset($tmp['cate'])? $tmp['cate']:[]
            ],*/
        ];

        unset($data);
        unset($tmp);
        return $chart;
    }

    /*
     * 返回商品库存
     */
    public function getItemStock()
    {
        $data = (new \yii\db\Query())
            ->select('name,amount')
            ->from('tbl_product')
            ->where('enable="Y" and wx_id=:wid',[':wid'=>Cache::getWid()])
            ->orderBy('amount asc')
            ->all();
        return $data;
    }
}
