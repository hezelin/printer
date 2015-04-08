<?php

namespace app\controllers;

class CodeapiController extends \yii\web\Controller
{
    public function actionBind()
    {
        return $this->render('bind');
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
