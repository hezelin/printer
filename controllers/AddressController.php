<?php

namespace app\controllers;
use app\models\DataCity;
use Yii;
use yii\web\Request;


class AddressController extends \yii\web\Controller
{
    public function actionCity()
    {
        return $this->render('city');
    }

    public function actionTree()
    {
        echo DataCity::getTree(Yii::$app->request->get('id'),Yii::$app->request->get('type'));
    }

    public function actionRegion()
    {
        return $this->render('region');
    }

}
