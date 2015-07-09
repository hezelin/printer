<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\models\Cache;
use app\modules\shop\models\TblShopOrder;
use app\modules\shop\models\TblShopOrderSearch;
use app\modules\shop\models\TblShopOrderCheck;
use app\modules\shop\models\TblShopOrderSend;
use Yii;
use yii\web\Controller;

class AdminorderController extends Controller
{
    public $layout = '/console';

    /*
     * 待审核订单
     */
    public function actionCheck()
    {
        $searchModel = new TblShopOrderCheck();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('check', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 批量审核通过
     */
    public function actionPut()
    {
        if(isset($_POST['mycheck']) && $_POST['mycheck']){
            Yii::$app->db->createCommand()
                ->update('tbl_order',array(
                    'order_status'=>4,
                    array('in','order_id',$_POST['mycheck'])
                ));
            echo json_encode(array('status'=>1));
            Yii::$app->end();
        }
    }

    /*
     * 审核不通过 + 留言
     */
    public function actionUnpass()
    {
        if(Yii::$app->request->post('data')){
            $wx_id = Cache::getWid();
            $row = TblShopOrder::updateAll(['order_status'=>2,'check_word'=>Yii::$app->request->post('text')],['wx_id'=>$wx_id,'order_id'=>Yii::$app->request->post('data')]);
            if($row)
                echo json_encode(['status'=>1]);
            else echo json_encode(['status'=>0,'msg'=>'系统错误!']);
        }else{
            echo json_encode(['status'=>0,'msg'=>'提交参数错误！']);
        }
    }

    /*
     * 审核通过,$send 等待发货，$wait ,等待取货
     */
    public function actionPass()
    {
        if($data = Yii::$app->request->post('data')){
            $wx_id = Cache::getWid();

            $send = $wait = [];
            foreach($data as $v){
                list($orderId,$status) = explode('|',$v);
                if($status == 2)
                    $wait[] = $orderId;
                else
                    $send[] = $orderId;
            }

            if($send)
                $row = TblShopOrder::updateAll(['order_status'=>5],['wx_id'=>$wx_id,'order_id'=>$send]);
            if($wait)
                $row = TblShopOrder::updateAll(['order_status'=>4],['wx_id'=>$wx_id,'order_id'=>$wait]);
            if(!$row)
                return json_encode(['status'=>0,'msg'=>'系统错误!']);
            return json_encode(['status'=>1]);
        }else
            return json_encode(['status'=>0,'msg'=>'参数错误']);
    }
    /*
     * 管理后台订单列表
     */
    public function actionList()
    {
        $searchModel = new TblShopOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 待发货
     */
    public function actionSend()
    {
        $searchModel = new TblShopOrderSend();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('send', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 发货 ajax 页面
     */
    public function actionAjax($order_id)
    {
        if(Yii::$app->request->isAjax){
            $type = Yii::$app->request->post('type');
            $no = Yii::$app->request->post('no');
            $model = TblShopOrder::findOne($order_id);
            if(!$model)
                return json_encode(['status'=>0,'msg'=>'订单不存在!']);
            $model->order_status = 6;
            $model->express = $type;
            if($type != 0)
                $model->express_num = $no;
            if($model->save())
                return json_encode(['status'=>1]);
            else
                return json_encode(['status'=>0,'msg'=>'入库失败!']);
        }else
            return json_encode(['status'=>0,'msg'=>'参数错误!']);
    }

    /*
     * 查看订单物流
     */
    public function actionExpress($type,$no)
    {
        if($no){
            $com = ['0','shunfeng','ems','shentong','yunda','yuantong','zhongtong','huitongkuaidi'];
            $apiUrl = 'http://api.kuaidi100.com/api?id=7152fcbc25d814f0&';
            $parmas = [
                'com'=>$com[$type],
                'nu'=>$no,
                'show'=>2
            ];

            $html = file_get_contents($apiUrl.join('&',$parmas));
            return $this->render('express',['html'=>$html]);
        }
    }

    /*
     * 订单取消（系统执行)
     */
    public function actionCancel()
    {
        if(Yii::$app->request->post('data')){
            $wx_id = Cache::getWid();
            $row = TblShopOrder::updateAll(['order_status'=>9,'check_word'=>Yii::$app->request->post('text')],['wx_id'=>$wx_id,'order_id'=>Yii::$app->request->post('data')]);
            if($row)
                echo json_encode(['status'=>1]);
            else echo json_encode(['status'=>0,'msg'=>'系统错误!']);
        }else{
            echo json_encode(['status'=>0,'msg'=>'提交参数错误！']);
        }
    }
}
