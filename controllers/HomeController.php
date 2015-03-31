<?php

namespace app\controllers;

class HomeController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 店铺装修后台
     */
    public function actionFitment()
    {
        return $this->render('fitment');
    }

    /*
     * 微官网
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 维修员官网
     */
    public function actionMaintain()
    {
        return $this->render('maintain');
    }

    /*
     * 官网设置
     */
    public function actionSetting()
    {
        return $this->render('setting');
    }

    /*
     * 样式选择
     */
    public function actionStyle()
    {
        return $this->render('style');
    }

}
