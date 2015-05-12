<?php

namespace app\controllers;

use app\models\TblMachineService;
use app\models\TblMachineServiceList;
use app\models\TblMachineServiceSearch;
use Yii;

class ServiceController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionDelete($id)
    {
        $model = TblMachineService::findOne($id)->delete();
        $this->redirect(['index']);

    }

    public function actionIndex()
    {
        $searchModel = new TblMachineServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionList()
    {
        $searchModel = new TblMachineServiceList();
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
