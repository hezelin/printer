<?php

namespace app\controllers;
use Yii;

class AdminRbacController extends \yii\web\Controller
{
    public $layout = '/weixin';

    public function actionPermission()
    {
        $auth = Yii::$app->authManager;
        return $this->render('permission',['auth'=>$auth]);
    }
}
