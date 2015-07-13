<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeProduct;
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
}
