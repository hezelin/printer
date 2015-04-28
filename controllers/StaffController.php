<?php

namespace app\controllers;

use app\models\Cache;
use yii\data\ActiveDataProvider;
use app\models\TblUserMaintain;
use app\models\TblUserMaintainSearch;
use Yii;


class StaffController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $searchModel = new TblUserMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
