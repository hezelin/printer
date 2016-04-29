<?php

namespace app\controllers;
use app\models\Cache;
use app\models\TblMachineSearch;
use app\models\TblRentApply;
use Yii;
use app\models\TblMachine;
use app\models\ToolBase;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


class MachineController extends \yii\web\Controller
{
    public $layout = 'console';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'force-delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
        $model = new TblMachine();

        if ($model->load(Yii::$app->request->post())) {

            $model->wx_id = Cache::getWid();
            $model->add_time = time();

            if( !$model->validate() ){                              // 验证数据是否完整
                return $this->render('add', ['model' => $model ]);
            }

            if( $model->amount == 1 ){
                if( $model->save() ){
                    $model->updateCount();                          // 更新计数
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else
                    Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors) );
            }else{
                $row =$model->multiSave();                          // 批量插入，并且自动更新统计
                if($row)
                    return $this->render('//tips/success',[
                        'tips'=>'成功添加 '.$row.' 个机器！',
                        'btnText'=>'继续添加',
                        'btnUrl'=>Url::toRoute(['add'])
                    ]);
            }

        }

        if(Yii::$app->request->get('model_id'))
            $model->model_id = Yii::$app->request->get('model_id');

        $model->come_from = 1;
        return $this->render('add', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->series_id = '~del~'.$model->series_id;
        $model->enable = 'N';
        if($model->save())
            $model->updateCount('dec');

        return $this->redirect(['list']);
    }

    /*
     * 删除真实数据
     */
    public function actionForceDelete($id)
    {
        $this->findModel($id)->delete();
        $model= TblRentApply::find()->where(['machine_id'=>$id])->one()->delete();

        return $this->redirect('/code/ahead');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $searchModel = new TblMachineSearch();
        $params = Yii::$app->request->queryParams;
        if( !isset($params['TblMachineSearch']['come_from']) )
            $params['TblMachineSearch']['come_from'] = 1;

        $dataProvider = $searchModel->search($params);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatus()
    {
        return $this->render('status');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else
            return $this->render('update', [ 'model' => $model ]);

    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = TblMachine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

}
