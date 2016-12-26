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
use app\models\analyze\TblAnalyzeRental;
use app\models\TblMachineService;
use app\modules\maintain\controllers\FaultController;

class AnalyzeController extends \yii\console\Controller
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

        //[20161215 自动评价
        // 20161219 Bug 修复
        $machine_service = new TblMachineService();
        $machine_service -> autoEvaluate();
        //20161215]

    }

    /*
     * 执行历史的分析
     */
    public function actionHistory($start=-4,$end=0)
    {
        set_time_limit(0);
        $ana = new TblAnalyzeFault();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeMaintain();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeOrder();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeProduct();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeMachine();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeRent();
        $ana->historyDay($start,$end);
        $ana = new TblAnalyzeRental();
        $ana->historyDay($start,$end);
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
    }

    /*
     * 机器统计
     */
    public function actionMachine()
    {
        $ana = new TblAnalyzeMachine();
        return $ana->historyDay(-4,0);
    }

    public function actionRent()
    {
        $ana = new TblAnalyzeRent();
        return $ana->historyDay(-4,0);
    }

    public function actionRental()
    {
        $ana = new TblAnalyzeRental();
        return $ana->historyDay(-4,0);
    }
}
