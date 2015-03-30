<?php

namespace app\controllers;

class CodeController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionBinding()
    {
        return $this->render('binding');
    }

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

}
