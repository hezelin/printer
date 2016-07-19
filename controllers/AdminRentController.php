<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblRentApplyCollect;
use app\models\TblRentApplyExpire;
use app\models\TblRentApplySearch;
use app\models\TblRentApplyWithMachine;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\ToolBase;
use app\models\views\ViewRentDataSearch;
use app\models\WxTemplate;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class AdminRentController extends \yii\web\Controller
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
            $model->due_time = ($_POST['TblRentApplyWithMachine']['due_time'] && $_POST['TblRentApplyWithMachine']['due_time'] != '1970-01-01')? strtotime($_POST['TblRentApplyWithMachine']['due_time']):0;
            $model->first_rent_time = ($_POST['TblRentApplyWithMachine']['first_rent_time'] && $_POST['TblRentApplyWithMachine']['first_rent_time'] != '1970-01-01')? strtotime($_POST['TblRentApplyWithMachine']['first_rent_time']):0;
            $model->status = 2;
            if($model->save()) {
                // 如果是审核通过，改版机器的状态 和 出租次数
                $model->updateMachineStatus();
                return $this->redirect(Url::toRoute(['map','id'=>$model->id]));
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

        if( $model->due_time < time() )
            $model->due_time = date('Y-m-d',strtotime('1 year'));

        if($model->first_rent_time)
            $model->first_rent_time = date('Y-m-d',$model->first_rent_time);

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
        $model->status = 11;
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
            $model->due_time = ($_POST['TblRentApply']['due_time'] && $_POST['TblRentApply']['due_time'] != '1970-01-01')? strtotime($_POST['TblRentApply']['due_time']):0;
            $model->first_rent_time = ($_POST['TblRentApply']['first_rent_time'] && $_POST['TblRentApply']['first_rent_time'] != '1970-01-01')? strtotime($_POST['TblRentApply']['first_rent_time']):0;
            if($model->save()) {
                return $this->render('//tips/success', [
                    'tips' => '资料修改成功',
                    'btnText' => '返回',
                    'btnUrl' => Url::toRoute(['/admin-rent/list'])
                ]);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        $model->due_time = date('Y-m-d',$model->due_time);
        $model->first_rent_time = date('Y-m-d',$model->first_rent_time);
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
        $searchModel = new ViewRentDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fixProvider = new ActiveDataProvider([
            'query' => TblUserMaintain::find()->where(['wx_id'=>Cache::getWid()]),
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
        $model->enable = 11;
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

            $model->due_time = ($_POST['TblRentApply']['due_time'] && $_POST['TblRentApply']['due_time'] != '1970-01-01')? strtotime($_POST['TblRentApply']['due_time']):0;
            $model->first_rent_time = ($_POST['TblRentApply']['first_rent_time'] && $_POST['TblRentApply']['first_rent_time'] != '1970-01-01')? strtotime($_POST['TblRentApply']['first_rent_time']):0;

            if($model->save()) {
                $model->updateMachineStatus();
                return $this->redirect(Url::toRoute(['map','id'=>$model->id]));
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        if($model->due_time)
            $model->due_time = date('Y-m-d',$model->due_time);
        if($model->first_rent_time)
            $model->first_rent_time = date('Y-m-d',$model->first_rent_time);
        else
            $model->first_rent_time = date('Y-m-d',strtotime('3 months',time()));

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
            $model->content = json_encode(['cover'=>['/images/call_maintain.png']]);
            $model->desc = Yii::$app->request->post('fault_text');
            $model->type = Yii::$app->request->post('fault_type');

            $model->remark = Yii::$app->request->post('fault_remark');
            if(!$model->save())
                exit(json_encode(['status'=>0,'msg'=>'错误100']));

            $fault_id = $model->id;
            $applyTime = $model->add_time;

            $model = new TblServiceProcess();
            $model->service_id = $fault_id;
            $model->content = '任务分配中';
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
            $model = TblRentApply::find()->where(['machine_id'=>$machine_id])->andWhere(['<','status',11])->one();
            $tpl = new WxTemplate(Yii::$app->request->post('wx_id'));
            $tpl->sendTask(
                $fault_id,
                Yii::$app->request->post('openid'),
                $name,Yii::$app->request->post('fault_text'),$model->address,
                $model->name.','.$model->phone,$model->add_time,
                Yii::$app->request->post('fault_remark')
            );

            // 为申请者推送消息
            $tpl->sendProcess(
                $from_openid,
                url::toRoute(['/maintain/fault/detail','id'=>Yii::$app->request->post('wx_id'),'fault_id'=>$fault_id],'http'),
                '任务已分配',
                $applyTime
            );
            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);
    }

    public function actionPhoneNoAllot($machine_id,$from_openid)
    {
        if(Yii::$app->request->post('fault_text')){

            $model = new TblMachineService();
            $model->machine_id = $machine_id;
            $model->weixin_id = Cache::getWid();
            $model->from_openid = $from_openid;
            $model->add_time = time();
            $model->status = 1;
            $model->content = json_encode(['cover'=>['/images/call_maintain.png']]);
            $model->desc = Yii::$app->request->post('fault_text');
            $model->type = Yii::$app->request->post('fault_type');
            $model->remark = Yii::$app->request->post('fault_remark');
            if(!$model->save())
                exit(json_encode(['status'=>0,'msg'=>'错误100']));

            // 为申请者推送消息
            $tpl = new WxTemplate(Yii::$app->request->post('wx_id'));
            $tpl->sendProcess(
                $from_openid,
                url::toRoute(['/maintain/fault/detail','id'=>Yii::$app->request->post('wx_id'),'fault_id'=>$model->id],'http'),
                '电话维修成功！',
                $model->add_time
            );
            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);
    }

    /*
     * 租借用户 定位
     * $id 为 租借表的id
     */
    public function actionMap($id)
    {
        $model = TblRentApply::findOne($id);
        if(!$model) throw new NotFoundHttpException();

        if( Yii::$app->request->post('lat') && Yii::$app->request->post('lng')){
            $data = ToolBase::bd_decrypt(Yii::$app->request->post('lat'),Yii::$app->request->post('lng'));
            $model->latitude = number_format($data['lat'],6,'.','');
            $model->longitude = number_format($data['lon'],6,'.','');

            if(Yii::$app->request->post('address-name'))
                $model->address = Yii::$app->request->post('address-name');

            if($model->save()){
                return $this->redirect(Url::toRoute('list'));
            }
        }

        if($model->latitude > 0)
        {
            $data  = ToolBase::bd_encrypt($model->latitude,$model->longitude);
            $model->latitude = $data['lat'];
            $model->longitude = $data['lon'];
        }

//        $model->latitude = 0.000000;
//        $model->longitude = 0.000000;
        return $this->render('map',['model'=>$model]);
    }

    /*
     * 快过期租借列表
     */
    public function actionExpire()
    {
        $searchModel = new TblRentApplyExpire();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('expire',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'wid'=>Cache::getWid()
        ]);
    }

    /*
     * 待收租租借列表
     */
    public function actionCollect()
    {
        $searchModel = new TblRentApplyCollect();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('collect',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'wid'=>Cache::getWid()
        ]);
    }

}
