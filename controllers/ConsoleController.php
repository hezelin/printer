<?php

namespace app\controllers;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionView()
    {
        return $this->render('view');
    }

}
