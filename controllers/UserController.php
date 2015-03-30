<?php

namespace app\controllers;

class UserController extends \yii\web\Controller
{
    public $layout = 'weixin';

    public function actionLog()
    {
        return $this->render('log');
    }

    public function actionReset()
    {
        return $this->render('reset');
    }

}
