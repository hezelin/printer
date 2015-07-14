<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblRentApplySearch;
use app\models\TblRentApplyList;
use app\models\TblRentApplyWithMachine;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\ToolBase;
use app\models\WxTemplate;
use Yii;
use yii\data\ActiveDataProvider;
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
        $model->first_rent_time = $model->first_rent_time? :'';
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
            $model->first_rent_time = ($model->first_rent_time && $model->first_rent_time != '1970-01-01')? strtotime($model->first_rent_time):0;
            $model->due_time = ($model->due_time && $model->due_time != '1970-01-01')? strtotime($model->due_time):0;

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

        $fixProvider = new ActiveDataProvider([
            'query' => TblUserMaintain::find(['wx_id'=>Cache::getWid()]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('list',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'fixProvider'=>$fixProvider,
            'wid'=>Cache::getWid()
        ]);
    }

    /*
     * 删除租借关系
     */
    public function actionDelete($id)
    {
        $model = TblRentApply::findOne($id);
        $model->enable = 'N';
        if($model->save())
            $model->updateMachineStatus('delete');

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

            $model->first_rent_time = ($model->first_rent_time && $model->first_rent_time != '1970-01-01')? strtotime($model->first_rent_time):0;
            $model->due_time = ($model->due_time && $model->due_time != '1970-01-01')? strtotime($model->due_time):0;


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

    /*
     * 分配维修，电话维修录入并且分配
     *
     * 更新 维修员 表 tbl_user_maintain 的待维修计数
     * 更新 维修记录表 tbl_machine_service`的状态 和 维修员
     * 维修进度表 tbl_service_process 插入分配任务
     * 获取用户资料  tbl_rent_apply 为维修员发送 任务的通知
     */

    public function actionPhonefault($machine_id,$from_openid)
    {
        if(Yii::$app->request->post('openid') && Yii::$app->request->post('fault_text')){

            $model = new TblMachineService();
            $model->machine_id = $machine_id;
            $model->weixin_id = Cache::getWid();
            $model->from_openid = $from_openid;
            $model->openid = Yii::$app->request->post('openid');
            $model->add_time = time();
            $model->status = 2;
            $model->cover = json_encode(['/images/call_maintain.png']);
            $model->desc = Yii::$app->request->post('fault_text');
            $model->type = Yii::$app->request->post('fault_type');
            if(!$model->save())
                exit(json_encode(['status'=>0,'msg'=>'错误100']));

            $fault_id = $model->id;
            $applyTime = $model->add_time;

            $model = new TblServiceProcess();
            $model->service_id = $fault_id;
            $model->content = json_encode(['status'=>2]);
            $model->add_time = time();
            if(!$model->save())
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,200']) );


            $model = TblUserMaintain::findOne([
                'wx_id'=>Yii::$app->request->post('wx_id'),
                'openid'=>Yii::$app->request->post('openid')
            ]);
            $name = $model->name;
            if(!$model)
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,300']) );
            $model->wait_repair_count = $model->wait_repair_count + 1;
            if( !$model->save() )
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,400']) );


            // 为维修员推送消息
            $model = TblRentApply::findOne(['machine_id'=>$machine_id,'enable'=>'Y']);
            $tpl = new WxTemplate(Yii::$app->request->post('wx_id'));
            $tpl->sendTask(
                $fault_id,
                Yii::$app->request->post('openid'),
                $name,Yii::$app->request->post('fault_text'),$model->address,
                $model->name.','.$model->phone,$model->add_time
            );

            // 为申请者推送消息
            $tpl->sendProcess(
                $from_openid,
                Url::toRoute(['s/detail','id'=>Yii::$app->request->post('wx_id'),'fault_id'=>$fault_id],'http'),
                '任务已分配',
                $applyTime
            );
            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);
    }
}
