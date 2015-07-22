<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;
use app\models\Cache;
use app\models\TblWeixin;
use yii\web\NotFoundHttpException;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 工作报表
     */
    public function actionView()
    {
        $id = Cache::getWid();
        $model = TblWeixin::find()
            ->where(['id'=>$id,'enable'=>'Y'])
            ->asArray()
            ->one();

        if( $model == null )
            throw new NotFoundHttpException('查看的页面不存在');

        /*
         * 保存 微信id,24小时
         */
        Cache::setValue('u:'.Yii::$app->user->id.':wid', $model['id'],60*60*24);

        Yii::$app->session['wechat'] = $model;

        // 是否选择公众号，跳转
        if( Yii::$app->request->get('url'))
            return $this->redirect( Yii::$app->request->get('url') );
        return $this->render('view');
    }

    /*
     * 数据统计
     */
    public function actionAnalyze()
    {
        $ana = new TblAnalyzeFault();

        $rent= new TblAnalyzeRent();

        $order = new TblAnalyzeOrder();

        $maintainer = new TblAnalyzeMaintain();

        $machine = new TblAnalyzeMachine();

        $item = new TblAnalyzeProduct();
        return $this->render('analyze',[
            'item'=>$item->getCharts(),
            'stock'=>$item->getItemStock(),
            'machine'=>$machine->getCharts(),
            'charts'=>$ana->getCharts(),
            'rent'=>$rent->getCharts(),
            'order'=>$order->getCharts(),
            'maintainer'=>$maintainer->getCharts()
        ]);
    }
}
