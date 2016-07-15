<?php

namespace app\controllers;

use app\models\Cache;
use app\models\ConfigBase;
use app\models\fault\NewCall;
use app\models\TblFaultCancelLog;
use app\models\TblMachineService;
use app\models\TblMachineServiceList;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\views\ViewFaultCancelSearch;
use app\models\views\ViewFaultDataSearch;
use app\models\views\ViewRentFaultMachineSearch;
use app\models\WxTemplate;
use yii\base\Exception;
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
        $faultStatus = $model->status;                  // 维修进度状态
        $model->status = 11;
        $model->opera_time = time();
        $fromOpenid = $model->from_openid;
        $toOpenid = $model->openid;
        $serviceId = $model->id;
        $applyTime = $model->add_time;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(!$model->save())                        // 更改维修资料表
                throw new Exception('维修表');

            $model = new TblFaultCancelLog();
            $model->service_id = $serviceId;
            $model->opera = $openid? $openid:'user:'.(Yii::$app->user->id);
            $model->status = $faultStatus;
            $model->opera_name = Yii::$app->user->isGuest? null:Yii::$app->user->identity->name;
            $model->type = $type;
            $model->add_time = time();
            $model->reason = $text;
            $model->wx_id = $id;
            if(!$model->save())
                throw new Exception('维修记录表');

//            if($faultStatus != 9)   {                   // 已经完成评价了，用户删除维修不影响维修员操作
                // 为管理员推送消息
            $tpl = new WxTemplate($id);
//                $url = Url::toRoute(['cancel','id'=>$model->id],'http');
            $url = '';
            $tpl->sendCancelService($fromOpenid,$url,$type==2? '您':'系统',$text,time(),$applyTime);

            if( $toOpenid ){
                $tpl->sendCancelService($toOpenid,$url,$type==2? '用户':'系统',$text,time(),$applyTime);
                // 维修员待修计数 减一
                $model = TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$toOpenid]);
                if( $model->wait_repair_count > 0)
                    $model->wait_repair_count -= 1;
                if(!$model->save())
                    throw new Exception('维修员待修计数');
            }
//            }

            $transaction->commit();
        }catch(\Exception $e) {
            $transaction->rollBack();
            if($type == 2)
                return $this->render('//tips/home-status',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
            return json_encode(['status'=>0,'msg'=>'入库失败!']);
        }

        if($type == 2)
            return $this->render('//tips/home-status',[
                'tips'=>'维修申请取消成功,正在返回首页...',
                'btnText'=>'返回首页',
                'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id]),
                'jumpUrl'=>Url::toRoute(['/wechat/index','id'=>$id]),
            ]);
        return json_encode(['status'=>1]);
    }

    /*
     * 待分配维修
     * $status ==1 ,待分配维修
     */
    public function actionIndex()
    {
        $searchModel = new ViewFaultDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);

        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'fixProvider'=> $searchModel->fixProvider(),
            'wid'=>Cache::getWid()
        ]);
    }

    public function actionList()
    {
        $searchModel = new ViewFaultDataSearch();
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
            'fixProvider'=> $fixProvider,
            'wid'=>Cache::getWid()
        ]);
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
                $fault->remark = Yii::$app->request->post('fault_remark');
                $fault->save();

                $process = new TblServiceProcess();
                $process->service_id = Yii::$app->request->post('id');
                $process->content = '任务分配中';
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
            $model = (new \yii\db\Query())
                ->select('name,address,phone')
                ->from('tbl_rent_apply')
                ->where(['machine_id'=>$machine_id])
                ->andWhere(['<','status',11])
                ->one();

            $tpl = new WxTemplate(Yii::$app->request->post('wid'));
            $tpl->sendTask(
                $rendId,
                Yii::$app->request->post('openid'),
                $name,$reason,$model['address'],
                $model['name'].','.$model['phone'],$applyTime,
                Yii::$app->request->post('fault_remark')
            );

            // 为申请者推送消息
            $tpl->sendProcess(
                $fromOpenid,
                url::toRoute(['/maintain/fault/detail','id'=>Yii::$app->request->post('wid'),'fault_id'=>$rendId],'http'),
                '任务分配中',
                $applyTime
            );
            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);

    }

    /*
     * 重新分配任务
     * post 提交 公众号wid,  维修员openid ，维修任务 id
     * 更新 维修员 表 tbl_user_maintain 的待维修计数
     * 更新 维修记录表 tbl_machine_service`的状态 和 维修员
     * 维修进度表 tbl_service_process 插入分配任务
     * 获取用户资料  tbl_rent_apply 为维修员发送 任务的通知
     */
    public function actionSwitch()
    {
        if( Yii::$app->request->post())
        {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            try {
                $fault = TblMachineService::findOne( Yii::$app->request->post('id') );
                $reason = ConfigBase::getFaultStatus($fault->type);
                $machine_id = $fault->machine_id;
                $rendId = $fault->id;

                // 旧维修员 计数减一
                $old = TblUserMaintain::findOne([
                    'wx_id'=>Yii::$app->request->post('wid'),
                    'openid'=>$fault->openid
                ]);
                $oldName = $old->name;
                if( $old->wait_repair_count > 0){
                    $old->wait_repair_count = $old->wait_repair_count - 1;
                    $old->save();
                }

                $fault->openid = Yii::$app->request->post('openid');
                $fault->save();

                $new = TblUserMaintain::findOne([
                    'wx_id'=>Yii::$app->request->post('wid'),
                    'openid'=>Yii::$app->request->post('openid')
                ]);
                $newName = $new->name;
                $new->wait_repair_count = $new->wait_repair_count + 1;
                $new->save();                 // 新维修员计数 加一


                $process = new TblServiceProcess();
                $process->service_id = Yii::$app->request->post('id');
                $process->content = '任务重新分配给 '.$newName;
                $process->add_time = time();
                $process->save();

                $transaction->commit();
            }catch(\Exception $e) {
                $transaction->rollBack();
                echo $e;
                echo json_encode(['status'=>0,'msg'=>'参数错误']);
                exit;
            }

            // 为维修员推送消息
            $model = TblRentApply::find()->where(['machine_id'=>$machine_id])->andWhere(['<','status',11])->one();
            $tpl = new WxTemplate(Yii::$app->request->post('wid'));
            $tpl->sendTask(
                $rendId,
                Yii::$app->request->post('openid'),
                $newName,$oldName.'转派。故障：'.$reason,$model->address,
                $model->name.','.$model->phone,$model->add_time,
                $fault->remark
            );

            echo json_encode(['status'=>1]);
        }
        else
            echo json_encode(['status'=>0,'msg'=>'参数错误']);

    }
    /*
     * 维修任务取消任务列表
     */
    public function actionCancelList()
    {
        $searchModel = new ViewFaultCancelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cancel-list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * 查看维修进度
     */
    public function actionProcess($id)
    {
        $model = TblMachineService::find()
            ->with(['machine'])
            ->where('tbl_machine_service.id=:id',[':id'=>$id])->one();

        if(!$model) {
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
     * 选择维修
     */
    public function actionSelect()
    {
        $searchModel = new TblMachineServiceList();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fromUrl = $this->dealUrl( Yii::$app->request->get('url') );

        return $this->render('select',['dataProvider'=>$dataProvider,'searchModel' => $searchModel,'wid'=>Cache::getWid(),'fromUrl'=>$fromUrl]);
    }

    /*
 * 处理 url
 */
    private function dealUrl($url)
    {
        if(strpos($url,'?') !== false){                     // 如果路径存在 问号 ？
            $arr = [];

            list($baseUrl,$query) = explode('?',urldecode($url) );
            if( $query ){
                foreach( explode('&',$query) as $q )
                {
                    if(!$q) continue;                       // 空白过滤
                    list($k,$v) = explode('=',$q);
                    if( $k == 'openid' )
                        continue;
                    $arr[$k] = $v;
                }
            }
            return $baseUrl . ($arr? '?'.http_build_query($arr).'&':'?');
        }

        return $url.'?';                                    //  没有问号 直接返回参数
    }

    /*
     * 电话维修
     */
    public function actionCall()
    {
        $searchModel = new ViewRentFaultMachineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fixProvider = new ActiveDataProvider([
            'query' => TblUserMaintain::find()->where(['wx_id'=>Cache::getWid()]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('call',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
            'fixProvider'=>$fixProvider,
            'wid'=>Cache::getWid()
        ]);
    }

    /*
     * 电话维修，新建资料
     */
    public function actionNewCall()
    {
        $model = new NewCall();
        if(Yii::$app->request->post())
        {
            if( $model->save() == 'success' ){
                Yii::$app->session->setFlash('success','资料录入成功！，请更正用户坐标！');
                return $this->redirect(['/adminrent/map','id'=>$model->rent->id]);
                /*return $this->render('//tips/success',[
                    'tips'=>'资料录入成功',
                    'btnText'=>'返回维修列表',
                    'btnUrl'=>Url::toRoute(['list'])
                ]);*/
            }else
                Yii::$app->session->setFlash('error','资料录入失败！');

        }

        return $this->render('newCall',[
            'machine'=>$model->machine,
            'rent'=>$model->rent,
            'fault'=>$model->fault,
            'maintainer'=>$model->getMaintainer()
        ]);
    }
}
