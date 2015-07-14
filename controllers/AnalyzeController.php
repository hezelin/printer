<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeProduct;

class AnalyzeController extends \yii\web\Controller
{
    /*
     * 维修统计
     */
    public function actionFault()
    {
        $ana = new TblAnalyzeFault();
        return $ana->historyDay(-30,0);
    }

    public function actionMaintainer()
    {
        return $this->render('maintainer');
    }

    public function actionOrder()
    {
        echo date('Y-m-d H:i:s',1436457600),'<br/>';
        echo date('Y-m-d H:i:s',1436544000),'<br/>';
//        return $this->render('order');
    }

    public function actionProduct()
    {
        $ana = new TblAnalyzeProduct();
//        return $ana->historyDay(-3,0);
        return $ana->today();
    }

    /*
     * 机器统计
     */
    public function actionMachine()
    {
        $ana = new TblAnalyzeMachine();
//        return $ana->historyDay(-3,0);
        return $ana->today();
    }

    public function actionRent()
    {
        return $this->render('rent');
    }

    public function actionUser()
    {
        return $this->render('user');
    }

}
