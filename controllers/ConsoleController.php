<?php

namespace app\controllers;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionView($id)
    {
        Yii::$app->session['weixinid'] = $id;
        return $this->render('view');
    }

}
