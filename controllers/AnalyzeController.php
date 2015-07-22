<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;
use app\models\TblMachineService;

class AnalyzeController extends \yii\web\Controller
{
    /*
     * 维修统计
     */
    public function actionFault()
    {
        $ana = new TblAnalyzeFault();
        return $ana->historyDay(-10,0);
    }

    /*
     * 维修员业绩统计
     */
    public function actionMaintainer()
    {
        $ana = new TblAnalyzeMaintain();
        return $ana->today();
    }

    public function actionOrder()
    {
        $ana = new TblAnalyzeOrder();
        return $ana->historyDay(-10,0);
    }

    public function actionProduct()
    {
        $ana = new TblAnalyzeProduct();
        return $ana->historyDay(-10,0);
//        return $ana->today();
    }

    /*
     * 机器统计
     */
    public function actionMachine()
    {
        $ana = new TblAnalyzeMachine();
        return $ana->historyDay(-10,0);
//        return $ana->today();
    }

    public function actionRent()
    {
        $ana = new TblAnalyzeRent();
        return $ana->historyDay(-10,0);
    }

    public function actionUser()
    {
        return $this->render('user');
    }

}
