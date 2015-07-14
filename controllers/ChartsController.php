<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeFault;
use Yii;

class ChartsController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionItem()
    {
        $ana = new TblAnalyzeProduct();
        return $this->render('item',['charts'=>$ana->getCharts()]);
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
}
