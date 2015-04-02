<?php

namespace app\controllers;

class TipsController extends \yii\web\Controller
{
    public function actionError()
    {
        return $this->render('error');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }

}
