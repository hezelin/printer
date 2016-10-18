<?php

namespace app\modules\shop\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = '/home2';
    public function actionIndex()
    {
        return $this->render('index');
    }

}
