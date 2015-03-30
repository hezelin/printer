<?php

namespace app\controllers;

class HomeController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionFitment()
    {
        return $this->render('fitment');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSetting()
    {
        return $this->render('setting');
    }

    public function actionStyle()
    {
        return $this->render('style');
    }

}
