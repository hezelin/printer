<?php

namespace app\modules\user\controllers;

use yii\web\Controller;


class DefaultController extends Controller
{
    public $layout = '/auicss';
    public function actionIndex()
    {
        return $this->render('index');
    }
}
