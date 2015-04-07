<?php

namespace app\controllers;

use app\models\TblWeixin;
use yii\web\NotFoundHttpException;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionView($id)
    {
        $model = TblWeixin::find()
            ->where(['id'=>$id,'enable'=>'Y'])
            ->asArray()
            ->one();

        if( $model == null )
            throw new NotFoundHttpException('查看的页面不存在');

        Yii::$app->session['wechat'] = $model;

        return $this->render('view');
    }

}
