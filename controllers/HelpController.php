<?php

namespace app\controllers;

class HelpController extends \yii\web\Controller
{
    public $layout = 'home';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionService($id)
    {
        return $this->render('service');
    }

}
