<?php

namespace app\controllers;

class IController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMachine()
    {
        return $this->render('machine');
    }

    public function actionScore()
    {
        return $this->render('score');
    }

    public function actionService()
    {
        return $this->render('service');
    }

}
