<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblRentApply;
use app\models\TblRentApplySearch;
use app\models\TblRentApplyList;
use app\models\TblRentApplyWithMachine;
use app\models\ToolBase;
use app\models\WxTemplate;
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
     * rent_id 租借id
     */
    public function actionPass($id)
    {
        $model = TblRentApplyWithMachine::find()
            ->where(['tbl_rent_apply.id'=>$id])
            ->joinWith('machineProject')
            ->one();

        if($model->load( Yii::$app->request->post()))
        {
            $model->due_time = $model->due_time? strtotime($model->due_time):0;
            $model->status = 2;

            if($model->save()) {
                // 如果是审核通过，改版机器的状态 和 出租次数
                $model->updateMachineStatus();

                return $this->render('//tips/success', [
                    'tips' => '审核成功，并且资料录入',
                    'btnText' => '返回',
                    'btnUrl' => Url::toRoute(['adminrent/apply'])
                ]);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));

            return $this->render('pass',['model'=>$model,'type'=>'update']);
        }

        $model->black_white = $model->machineProject->black_white;
        $model->colours = $model->machineProject->colours;
        $model->monthly_rent = $model->machineProject->lowest_expense;
        $model->machine_id = '';

        return $this->render('pass',[
            'model'=>$model,
            'type'=>'allot',
            'tips'=>'补充资料录入，完成租借记录'
        ]);
    }

    /*
     * 不通过
     * $openid,$url,$first,$key1,$key2,$remark
     */
    public function actionNopass($rent_id)
    {
        $model = TblRentApply::findOne($rent_id);
        $model->enable = 'N';
        if(!$model->save())
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'入库失败']));

        $wx_id = Cache::getWid();
        // 为管理员推送消息
        $tpl = new WxTemplate($wx_id);
        $text = Yii::$app->request->post('text');
        $tpl->sendCheck($model['openid'],'','您提交的租借机器申请已完成审核。','不通过',$text,'请您补充并修改资料后重新提交，谢谢。');
        echo json_encode(['status'=>1]);
    }

    /*
     * 租借申请审核，资料录入
     */
    public function actionUpdate($id)
    {
        $model = TblRentApply::findOne($id);

        if($model->load( Yii::$app->request->post()))
        {
            $model->due_time = $model->due_time? strtotime($model->due_time):0;

            if($model->save()) {
                return $this->render('//tips/success', [
                    'tips' => '资料修改成功',
                    'btnText' => '返回',
                    'btnUrl' => Url::toRoute(['adminrent/list'])
                ]);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        return $this->render('pass',[
            'model'=>$model,
            'type'=>'update',
            'tips'=>'修改租借资料',
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

    /*
     * 为机器 和 用户 绑定 租赁关系，第一次资料录入
     */
    public function actionBings($machine_id,$openid)
    {
        $model = new TblRentApply();

        if($model->load( Yii::$app->request->post()))
        {
            $model->openid = $openid;
            $model->machine_id = $machine_id;
            $model->project_id = 0;
            $model->add_time = time();
            $model->wx_id = Cache::getWid();
            $model->status = 2;

            $model->due_time = $model->due_time? strtotime($model->due_time):0;

            if($model->save()) {
                $model->updateMachineStatus();
                return $this->render('//tips/success', [
                    'tips' => '机器绑定租借成功',
                    'btnText' => '返回机器列表',
                    'btnUrl' => Url::toRoute(['machine/list'])
                ]);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }



        return $this->render('pass',[
            'model'=>$model,
            'type'=>'update',
            'tips'=>'绑定用户租借关系，录入租借资料'
        ]);
    }

}
