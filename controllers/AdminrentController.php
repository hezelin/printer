<?php

namespace app\controllers;

use app\models\DataCity;
use app\models\TblMachine;
use app\models\TblRentApply;
use app\models\TblRentApplySearch;
use app\models\TblRentApplyList;
use app\models\ToolBase;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class AdminrentController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 解绑必须 post 提交
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /*
     * 关联查询用户资料
     */
    public function actionApply()
    {
        $searchModel = new TblRentApplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('apply',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * 租借申请审核，资料录入
     */
    public function actionCheck($id)
    {
        $model = TblRentApply::findOne($id);

        if($model->load( Yii::$app->request->post()))
        {
            $model->due_time = $model->due_time? strtotime($model->due_time):0;
            if($model->save())
                return $this->render('//tips/success',[
                    'tips'=>'审核成功，并且资料录入',
                    'btnText'=>'返回',
                    'btnUrl'=>Url::toRoute(['adminrent/apply'])
                ]);

            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        if( $model->region )
            $model->areaText = DataCity::getAddress($model->region);

        return $this->render('check',[
            'model'=>$model,
            'province'=>DataCity::$province,
            'city' => DataCity::$city,
            'region' => DataCity::$region,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $searchModel = new TblRentApplyList();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * 删除租借关系
     */
    public function actionDelete($id)
    {
        TblRentApply::findOne($id)->delete();
        return $this->redirect(['list']);
    }

}
