<?php
/*
 * 数据分析 ，每天定时跑
 */
namespace app\commands;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;

class AnalyzeController extends \yii\console\Controller
{
    /*
     * 每天跑的分析
     */
    public function actionToday()
    {
        set_time_limit(0);
        $ana = new TblAnalyzeFault();
        $ana->today();
        $ana = new TblAnalyzeMaintain();
        $ana->today();
        $ana = new TblAnalyzeOrder();
        $ana->today();
        $ana = new TblAnalyzeProduct();
        $ana->today();
        $ana = new TblAnalyzeMachine();
        $ana->today();
        $ana = new TblAnalyzeRent();
        $ana->today();
    }
    /*
     * 维修统计
     */
    public function actionFault()
    {
        $ana = new TblAnalyzeFault();
        return $ana->historyDay(-4,0);
    }

    /*
     * 维修员业绩统计
     */
    public function actionMaintainer()
    {
        $ana = new TblAnalyzeMaintain();
        return $ana->historyDay(-4,0);
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
        return $ana->historyDay(-4,0);
    }
}
