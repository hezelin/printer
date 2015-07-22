<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeRent;
use Yii;

class ChartsController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionItem()
    {
        $ana = new TblAnalyzeProduct();
        return $this->render('item',['charts'=>$ana->getCharts(),'stock'=>$ana->getItemStock()]);
    }

    /*
     * 机器库存变化图片
     */
    public function actionMachine()
    {
        $ana = new TblAnalyzeMachine();
        return $this->render('machine',['charts'=>$ana->getCharts()]);
    }

    /*
     * 维修统计
     */
    public function actionFault()
    {
        $ana = new TblAnalyzeFault();
        return $this->render('fault',['charts'=>$ana->getCharts()]);
    }
    /*
     * 租借统计
     */
    public function actionRent()
    {
        $ana = new TblAnalyzeRent();
        return $this->render('rent',['charts'=>$ana->getCharts()]);
    }

    /*
     * 订单统计
     */
    public function actionOrder()
    {
        $ana = new TblAnalyzeOrder();
        return $this->render('order',['charts'=>$ana->getCharts()]);
    }

    /*
     * 维修员统计
     */
    public function actionMaintainer()
    {
        $ana = new TblAnalyzeMaintain();
//        print_r( $ana->getCate(201401,201406) );
        return $this->render('maintainer',['charts'=>$ana->getCharts()]);
    }
}
