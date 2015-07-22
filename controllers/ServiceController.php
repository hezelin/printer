<?php

namespace app\controllers;

use app\models\Cache;
use app\models\ConfigBase;
use app\models\TblFaultCancelLog;
use app\models\TblFaultCancelLogSearch;
use app\models\TblMachineService;
use app\models\TblMachineServiceList;
use app\models\TblMachineServiceSearch;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\WxTemplate;
use yii\data\ActiveDataProvider;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ServiceController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionAdd()
    {
        return $this->render('add');
    }

    /*
     *  $id,公众号id,
     *  $fid 维修申请id
     */
    public function actionDelete($id,$fid)
    {
        $this->layout = 'home';

        $type = Yii::$app->request->post('type');
        $text = Yii::$app->request->post('text');
        $openid = Yii::$app->request->post('openid');

        $model = TblMachineService::findOne($fid);
        $model->enable = 'N';
        $model->opera_time = time();
        $fromOpenid = $model->from_openid;
        $toOpenid = $model->openid;
        $serviceId = $model->id;
        $applyTime = $model->add_time;

        if( !$model->save() )
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'错误1']));

        $faultStatus = $model->status;          // 维修进度状态
        $model = new TblFaultCancelLog();
        $model->service_id = $serviceId;
        $model->opera = $openid? $openid:'user:'.(Yii::$app->user->id);
        $model->type = $type;
        $model->add_time = time();
        $model->reason = $text;
        $model->wx_id = $id;

        if(!$model->save())
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'错误2']));

        if($faultStatus < 8)   {
            // 为管理员推送消息
            $tpl = new WxTemplate($id);
            $url = Url::toRoute(['cancel','id'=>$model->id]);
            $tpl->sendCancelService($fromOpenid,$url,$type==2? '您':'系统',$text,time(),$applyTime);
            $tpl->sendCancelService($toOpenid,$url,$type==2? '用户':'系统',$text,time(),$applyTime);
            // 用户待修计数 减一
            $model = TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$toOpenid]);
            if(!$model)
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,300']) );
            $model->wait_repair_count = $model->wait_repair_count - 1;
            if( !$model->save() )
                Yii::$app->end( json_encode(['status'=>0,'msg'=>'出错,400']) );
        }


        if($type == 2)
            return $this->render('//tips/homestatus',['tips'=>'维修申请取消成功！','btnText'=>'返回','btnUrl'=>Url::toRoute(['i/machine','id'=>$id])]);
        return json_encode(['status'=>1]);
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
            'fixProvider'=>$fixProvider,
            'wid'=>Cache::getWid()
        ]);
    }

    public function actionList()
    {
        $searchModel = new TblMachineServiceList();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel,'wid'=>Cache::getWid()]);
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


            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            try {
                $model->save();                 // 维修员计算 加一

                $fault = TblMachineService::findOne( Yii::$app->request->post('id') );
                $reason = ConfigBase::getFaultStatus($fault->type);
                $machine_id = $fault->machine_id;
                $rendId = $fault->id;
                $fromOpenid = $fault->from_openid;
                $applyTime = $fault->add_time;


                $fault->openid = Yii::$app->request->post('openid');
                $fault->status = 2;
                $fault->save();

                $process = new TblServiceProcess();
                $process->service_id = Yii::$app->request->post('id');
                $process->content = json_encode(['status'=>2]);
                $process->add_time = time();
                $process->save();

                $transaction->commit();
            }catch(\Exception $e) {
                $transaction->rollBack();
//                echo $e;
                echo json_encode(['status'=>0,'msg'=>'参数错误']);
                exit;
            }

            // 为维修员推送消息
            $model = TblRentApply::findOne(['machine_id'=>$machine_id,'enable'=>'Y']);
            $tpl = new WxTemplate(Yii::$app->request->post('wid'));
            $tpl->sendTask(
                $rendId,
                Yii::$app->request->post('openid'),
                $name,$reason,$model->address,
                $model->name.','.$model->phone,$model->add_time
            );

            // 为申请者推送消息
            $tpl->sendProcess(
                $fromOpenid,
                Url::toRoute(['s/detail','id'=>Yii::$app->request->post('wid'),'fault_id'=>$rendId],'http'),
                '任务分配中',
                $applyTime
            );
            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);

    }

    /*
     * 维修任务取消任务列表
     */
    public function actionCancellist()
    {
        $searchModel = new TblFaultCancelLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cancellist',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * 查看维修进度
     */
    public function actionProcess($id)
    {
        if (($model = TblMachineService::find($id)->with([
                'machine'=>function($query){
                    $query->joinWith('machineModel');
                }
            ])->one() ) == null) {
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

    /*
     * 取消原因
     * $id 取消列表的id
     */
    public function actionCancel($id)
    {

    }
}
