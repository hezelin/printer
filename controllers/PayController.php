<?php

namespace app\controllers;

class PayController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionWeixin()
    {
        return $this->render('weixin');
    }

}
