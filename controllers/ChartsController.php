<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeRent;
use app\models\analyze\TblAnalyzeRental;
use app\models\TblRentReportLog;
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

    /*
     * 收租 总数统计 ，按月
     */
    public function actionRental()
    {
        $ana = new TblAnalyzeRental();
        return $this->render('rental',['charts'=>$ana->getRentalCharts()]);
    }
    /*
     * 单个机器 租金统计
     */
    public function actionMachineRental($machine_id)
    {
        $ana = new TblAnalyzeRental();
        $searchModel = new TblRentReportLog();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,rent_period,t.name,t.address,first_rent_time,
                p.type,m.series_id,p.brand_name as brand')
            ->from('tbl_rent_apply t')
            ->leftJoin('tbl_machine m','t.machine_id=m.id')
            ->leftJoin('tbl_machine_model p','p.id=m.model_id')
            ->where('t.machine_id=:mid and t.enable="Y"',[':mid'=>$machine_id])
            ->orderBy('t.id desc')
            ->one();

        return $this->render('machineRental',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rent'=>$rent,
            'charts'=>$ana->getCharts()
        ]);
    }

}
