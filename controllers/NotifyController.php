<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblActivity;
use app\models\TblNotifyLog;
use app\models\TblNotifyLogSearch;
use app\models\TblUserWechat;
use Yii;
use yii\helpers\Url;

class NotifyController extends \yii\web\Controller
{
    public $layout = 'console';
    public function actionList()
    {
        $searchModel = new TblNotifyLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * $id 公众号id
     * $openid 接收者id
     */
    public function actionSend()
    {
        $id = Yii::$app->request->get('id')? :Cache::getWid();
        $openid = \Yii::$app->request->get('openid');

        $model = new TblNotifyLog();

        if($model->load(Yii::$app->request->post())){
            $model->openid = $openid;
            $model->add_time = time();
            $model->from_id = Yii::$app->user->id;
            $model->wx_id = $id;
            if( $model->save() ){
                return $this->render('//tips/success',[
                    'tips' => '发布通知成功',
                    'btnText' => '继续发布',
                    'btnUrl' => Url::toRoute(['send','id'=>$id])
                ]);
            }
            else
                Yii::$app->session->setFlash('error',\app\models\ToolBase::arrayToString($model->errors));
        }

        $user = $openid? TblUserWechat::findOne(['wx_id'=>$id,'openid'=>$openid]):NULL;
        return $this->render('send',['user'=>$user,'model'=>$model]);
    }

    /*
     * 通知消息 id
     */
    public function actionDelete($id)
    {
        $model = TblNotifyLog::findOne($id);
        $model->enable = 'N';
        $model->save();
        $this->redirect(['list']);
    }

    /*
     * 最新活动
     */
    public function actionActivity()
    {
        $wxId = Cache::getWid();
        $model = TblActivity::findOne($wxId);
        if($model == null)
        {
            $model = new TblActivity();
            $model->wx_id = $wxId;
        }

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            $model->created_at = time();
            if($model->save())
                Yii::$app->session->setFlash('success','保存成功');
            else
                Yii::$app->session->setFlash('error','保存失败');
        }
        return $this->render('activity',['model'=>$model]);
    }
}
