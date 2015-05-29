<?php

namespace app\controllers;

use app\models\TblUserWechatSearch;
use Yii;

class WxuserController extends \yii\web\Controller
{
    public $layout = 'console';
    public function actionList()
    {
        $searchModel = new TblUserWechatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionSelect()
    {
        return $this->render('select');
    }

}
