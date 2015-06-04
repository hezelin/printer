<?php

namespace app\modules\shop\controllers;

use yii\web\Controller;

class BackendController extends Controller
{
    public $layout = '/console';

    /*
     * 添加宝贝
     */
    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionList()
    {
        return $this->render('list');
    }
}
