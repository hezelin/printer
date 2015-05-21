<?php

namespace app\controllers;

use app\models\Cache;
use app\models\ConfigBase;
use app\models\TblMachineService;
use app\models\TblMachineServiceList;
use app\models\TblMachineServiceSearch;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\WxTemplate;
use yii\data\ActiveDataProvider;

use Yii;
use yii\web\NotFoundHttpException;

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

        $fixProvider = new ActiveDataProvider([
            'query' => TblUserMaintain::find(['wx_id'=>Cache::getWid()]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'fixProvider'=>$fixProvider
        ]);
    }

    public function actionList()
    {
        $searchModel = new TblMachineServiceList();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * 接收分配的任务
     * post 提交 公众号wid,  维修员openid ，维修任务 id
     * 更新 维修员 表 tbl_user_maintain 的待维修计数
     * 更新 维修记录表 tbl_machine_service`的状态 和 维修员
     * 维修进度表 tbl_service_process 插入分配任务
     * 获取用户资料  tbl_rent_apply 为维修员发送 任务的通知
     */
    public function actionAllot()
    {
        if( Yii::$app->request->post())
        {
            $model = TblUserMaintain::findOne([
                'wx_id'=>Yii::$app->request->post('wid'),
                'openid'=>Yii::$app->request->post('openid')
            ]);
            $name = $model->name;
            if(!$model)
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,100']) );
            $model->wait_repair_count = $model->wait_repair_count + 1;
            if( !$model->save() )
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,200']) );


            $model = TblMachineService::findOne( Yii::$app->request->post('id') );
            $reason = ConfigBase::getFaultStatus($model->type);
            $machine_id = $model->machine_id;
            $rendId = $model->id;

            if(!$model)
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,300']) );
            $model->openid = Yii::$app->request->post('openid');
            $model->status = 2;
            if( !$model->save() )
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,400']) );

            $model = new TblServiceProcess();
            $model->service_id = Yii::$app->request->post('id');
            $model->content = json_encode(['text'=>'维修任务已分配'],JSON_UNESCAPED_UNICODE);
            $model->add_time = time();
            if(!$model->save())
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,500']) );


            // 为维修员推送消息
            $model = TblRentApply::findOne(['machine_id'=>$machine_id,'enable'=>'Y']);

            $tpl = new WxTemplate(Yii::$app->request->post('wid'));
            $tpl->sendTask(
                $rendId,
                Yii::$app->request->post('openid'),
                $name,$reason,$model->address,
                $model->name.','.$model->phone,$model->add_time
            );

            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);

    }
    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

    /*
     * 查看维修进度
     */
    public function actionProcess($id)
    {
        if (($model = TblMachineService::find($id)->with('machine')->one() ) == null) {
            throw new NotFoundHttpException('这个页面不存在');
        }
//        维修进度
        $process = (new \yii\db\Query())
            ->select('process,content,add_time')
            ->from('tbl_service_process')
            ->where(['service_id' => $id])
            ->orderBy('id desc')
            ->all();

        return $this->render('process', [
            'model' => $model,
            'process'=>$process
        ]);
    }
}
