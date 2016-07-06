<?php
/*
 * 主动接单
 * 任务列表 等待
 */
namespace app\modules\maintain\controllers;

use yii\web\Controller;


class TaskController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
