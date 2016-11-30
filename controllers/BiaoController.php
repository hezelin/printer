<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeRent;
use app\models\analyze\TblAnalyzeRental;
use app\models\TblNotifyLog;
use app\models\WxChat;
use app\models\WxTemplate;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use app\models\Cache;

class BiaoController extends \yii\web\Controller
{
    public $layout = 'weixin';


    private function actionShow(){
        $from_openid = "ohTunt7iEjCJMp0p-C9J-Tcz-Mps";
        $tpl = new WxTemplate(Yii::$app->request->get('wx_id'));
        $tpl->sendProcess(
            $from_openid,
            url::toRoute(['/maintain/fault/detail', 'id' => Yii::$app->request->get('wx_id'), 'fault_id' => 2], 'http'),
            '电话维修成功！',
            time()
        );
    }

    private function actionSendNotify(){
        $from_openid = "ohTunt7iEjCJMp0p-C9J-Tcz-Mps";
        $tpl = new WxTemplate(Yii::$app->request->get('wx_id'));


        $tpl->sendNotify($from_openid, "您好，你有新的通知：", "测试", date("m月d日 H:i",time()), "没有留言！");
    }

    private function actionGet(){
        $query = new Query();
        $info = $query->select('*')
            ->from('tbl_weixin_template')
            ->all();
        echo "<pre>";
        var_dump($info);

    }

    /*
    * {{first.DATA}}
    内容：{{keyword1.DATA}}
    时间：{{keyword2.DATA}}
    {{remark.DATA}}
    */
    private function sendNotify22($openid,$url,$first,$key1,$key2,$remark)
    {
        //OPENTM405766411
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('checkInfo'),
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>$first,
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>$key1,
                    'color'=>'#000000',
                ],
                'keyword2'=>[
                    'value'=>$key2,
                    'color'=>'#000000',
                ],
                'remark'=>[
                    'value'=>$remark,
                    'color'=>'#ff0000',
                ],
            ]
        ];
        return $this->sendTpl($tpl);
    }

    public function actionRedirect(){
//        $data = [
//            'id' => 'ohTunt7iEjCJMp0p-C9J-Tcz-Mps'
//        ];
//        $wx = new WxChat();
        //return $this->redirect([Url::toRoute('/admin-score/send'),'id' =>1]);
    }

    public function actionTest(){
        $start = strtotime(date('Y-m-d',strtotime('-10 day')));
        $end = strtotime(date('Y-m-d',strtotime('-1 day')));
        $query = new Query();
        $info = $query
            ->select('*')
            ->from('tbl_analyze_rent')
            ->all();
        // ->where('wx_id = :wx_id', [':wx_id' => Cache::getWid()])
            //->where(['between','date_time',$start,$end])
            //->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])


        echo "<pre>";
        var_dump($info);
        //var_dump($query);
        echo "<hr>";
        echo "<pre>";
        $model = new TblAnalyzeRent();
        var_dump($model ->getCharts());

        echo "<hr>";
        echo date("Y:m:d H:i:s", strtotime('-10 day'))."<br>";
        echo date("Y:m:d H:i:s", strtotime('-10 days'))."<br>";
        echo Cache::getWid()."<br>";

        echo "<hr>";
        $start = strtotime(date('Y-m-d',strtotime('-10 day')));
        $end = strtotime(date('Y-m-d',strtotime('-1 day')));
        echo "start:".$start." = ".date('Y-m-d H:i:s',$start)."<br>";
        echo "end:".$end." = ".date('Y-m-d H:i:s', $end)."<br>";
        $data = (new \yii\db\Query())
            ->select('date_time,total_count,add_count,collect_count,expire_count')
            ->from('tbl_analyze_rent')
            ->where(['between','date_time',$start,$end])
            ->andWhere('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->all();
        var_dump($data);

    }

    public function actionLocal(){
        $query = new Query();
        $info = $query->select('*')
            -> from('tbl_analyze_rent')
            //->where('wx_id = :wx_id', [':wx_id' => 1])
            ->all();

        echo "<pre>";
        var_dump($info);
    }
    public function actionUpdateAll(){
        $id = 1;
        $openid= 'oXMyut1RFKZqchW8qt_6h0OT8FN4';
        echo TblNotifyLog::updateAll(['is_read' => 'Y'], 'wx_id = :id and openid = :openid and is_read = :is_read',['id' => $id,'openid' =>$openid, 'is_read' => 'N']);

    }

}
