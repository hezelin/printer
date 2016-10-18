<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;
use app\models\analyze\TblAnalyzeRental;

class AnalyzeController extends \yii\web\Controller
{
    /*
     * 每天跑的分析
     */
    public function actionToday()
    {
        set_time_limit(0);
        $ana = new TblAnalyzeFault();
        $ana->yesterday();
        $ana = new TblAnalyzeMaintain();
        $ana->today();
        $ana = new TblAnalyzeOrder();
        $ana->yesterday();
        $ana = new TblAnalyzeProduct();
        $ana->yesterday();
        $ana = new TblAnalyzeMachine();
        $ana->yesterday();
        $ana = new TblAnalyzeRent();
        $ana->yesterday();
        $ana = new TblAnalyzeRental();
        $ana->yesterday();
    }
    /*
     * 维修统计
     */
    public function actionHistory()
    {
        $ana = new TblAnalyzeFault();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeMaintain();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeOrder();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeProduct();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeMachine();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeRent();
        $ana->historyDay(-15,0);
        $ana = new TblAnalyzeRental();
        $ana->historyDay(-15,0);
    }

    /*
     * 维修员业绩统计
     */
    public function actionMaintainer()
    {
        $ana = new TblAnalyzeMaintain();
        return $ana->today();
//        return $ana->today();
    }

    public function actionOrder()
    {
        $ana = new TblAnalyzeOrder();
        return $ana->historyDay(-4,0);
    }

    public function actionProduct()
    {
        $ana = new TblAnalyzeProduct();
        return $ana->historyDay(-4,0);
//        return $ana->today();
    }

    /*
     * 机器统计
     */
    public function actionMachine()
    {
        $ana = new TblAnalyzeMachine();
        return $ana->historyDay(-4,0);
//        return $ana->today();
    }

    public function actionRent()
    {
        $ana = new TblAnalyzeRent();
        return $ana->yesterday();
//        return $ana->historyDay(-4,-3);
    }

    public function actionRental()
    {
        $ana = new TblAnalyzeRental();
        return $ana->today();
    }
    /*
     * 修正最近10天数据
     */
    public function actionAmend()
    {

    }
}
