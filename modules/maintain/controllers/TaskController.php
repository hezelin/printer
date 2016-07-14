<?php
/*
 * 主动接单
 * 任务列表 等待
 */
namespace app\modules\maintain\controllers;

use yii\web\Controller;
use app\models\fault\FaultList;
use app\models\ConfigBase;
use app\models\WxBase;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\TblMachineService;
use app\models\TblServiceProcess;
use app\models\WxTemplate;
use Yii;


class TaskController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = '/auicss';

    /*
     * 主动接单
     * $id公众号id
     */
    public function actionInitiative($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id, t.content as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.status<11')
            ->where(['t.status' => 1,'t.weixin_id'=>$id])
            ->orderBy('t.id desc')
            ->all();

        foreach ($model as $i=>$m) {
            $content = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $content['cover'][0];
        }
        return $this->render('initiative',['model'=>$model,'count'=>count($model)]);
    }

    /*
     * 维修中、未评价、历史记录，type = process, evaluate,history
     * $id 为公众号id
     */
    public function actionList($id)
    {
        $data = new FaultList($id);
        return $this->render('list',['model'=>$data->task(),'id'=>$id]);
    }

    /*
     * 维修任务详情
     * 维修申请 id, status = 2
     */
    public function actionDetail($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.content as fault_cover,t.desc,t.type as fault_type, t.remark, t.add_time,t.status,t.fault_score,
                    t.machine_id as mid,t.unfinished_parts_num, m.address,m.name,m.phone,t.weixin_id as wx_id,
                    m.latitude,m.longitude,t.from_openid,p.maintain_count as fault_num
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id')
            ->leftJoin('tbl_machine as p','p.id=t.machine_id')
            ->where(['t.id' => $id])
            ->one();
        if(!$model)
            throw new BadRequestHttpException();

        // 图片预览 路径设置
        $contents = json_decode($model['fault_cover'],true);
        $model['fault_cover'] = Yii::$app->request->hostInfo.$contents['cover'][0];
        foreach($contents['cover'] as $cover)
            $model['cover_images'][] = Yii::$app->request->hostinfo.$cover;
        if( isset($contents['voice'])){
            $model['voice_url'] = $contents['voice'];
            $model['voice_len'] = $contents['voiceLen'];
        }

        $openid = WxBase::openId($model['wx_id']);

        $status = $model['status'];
        switch($status){
            case 1: return $this->render('detail',['model'=>$model,'openid'=>$openid,'from'=>'initiative']);
            case 2: return $this->render('detail',['model'=>$model,'openid'=>$openid,'from'=>'allot']);
            case 3: return $this->render('process-scan', [
                'model' => $model,
                'openid' => $openid,
                'mUrl' => Url::toRoute(['/codeapi/machine','id'=>$model['mid']],'http'),
                'btnHtml'=>Html::a(
                    ConfigBase::getFixMaintainStatus($status),
                    '#',
                    [
                        'data-ajax'=>1,
                        'data-status'=>$status+1,
                        'class'=>'process-btn aui-fixed-bottom',
                        'data-href'=>Url::toRoute(['/maintain/task/process-ajax','id'=>$model['id'],'openid'=>$openid])
                    ]
                )
            ]);
            case 4: return $this->render('process', [
                'model' => $model,
                'openid' => $openid,
                'btnHtml'=>Html::a(
                    ConfigBase::getFixMaintainStatus($status),
                    url::toRoute(['/maintain/fault/affirmfault','id'=>$model['wx_id'],'fault_id'=>$model['id'],'openid'=>$openid]),
                    [
                        'data-ajax'=>0,
                        'data-status'=>$status+1,
                        'class'=>'process-btn aui-fixed-bottom',
                    ]
                )
            ]);
            case 5: return $this->render('process', [
                'model' => $model,
                'openid' => $openid,
                'btnHtml'=>Html::tag(
                    'div',
                    Html::a(
                        '维修完成',
                        Url::toRoute(['/maintain/task/process-ajax','id'=>$model['id'],'openid'=>$openid]),
                        [
                            'data-ajax'=>2,
                            'data-status'=>8,
                            'class'=>'process-btn'
                        ]).
                    Html::a(
                        ConfigBase::getFixMaintainStatus($status),
                        url::toRoute(['/maintain/fault/applyparts','id'=>$model['wx_id'],'fault_id'=>$model['id']]),
                        [
                            'data-ajax'=>0,
                            'data-status'=>$status+1,
                            'class'=>'process-btn'
                        ]),
                    ['class'=>'aui-fixed-bottom aui-fixed-2']
                )
            ]);
            case 6:
                if($model['unfinished_parts_num'] <= 0){
                    return $this->render('process', [
                        'model' => $model,
                        'openid' => $openid,
                        'btnHtml'=>Html::tag(
                            'div',
                            Html::a(
                                '维修完成',
                                Url::toRoute(['/maintain/task/process-ajax','id'=>$model['id'],'openid'=>$openid]),
                                [
                                    'data-ajax'=>2,
                                    'data-status'=>8,
                                    'class'=>'process-btn'
                                ]).
                            Html::a(
                                '申请配件',
                                url::toRoute(['/maintain/fault/applyparts','id'=>$model['wx_id'],'fault_id'=>$model['id']]),
                                [
                                    'data-ajax'=>0,
                                    'data-status'=>$status+1,
                                    'class'=>'process-btn'
                                ]),
                            ['class'=>'aui-fixed-bottom aui-fixed-2']
                        )
                    ]);
                }
                return $this->render('process', [
                    'model' => $model,
                    'openid' => $openid,
                    'btnHtml'=> Html::tag('div',
                        Html::a(
                            '配件进度',
                            Url::toRoute(['/shop/parts/process','id'=>$model['wx_id'],'fault_id'=>$model['id']]),
                            ['data-ajax'=>0,'class'=>'process-btn']
                        ),
                        ['class'=>'aui-fixed-bottom'] )
                ]);
            case 7: return $this->render('process', [
                'model' => $model,
                'openid' => $openid,
                'btnHtml'=>Html::a(
                    ConfigBase::getFixMaintainStatus($status),
                    Url::toRoute(['/maintain/task/process-ajax','id'=>$model['id'],'openid'=>$openid]),
                    [
                        'data-ajax'=>1,
                        'data-status'=>$status+1,
                        'class'=>'process-btn aui-fixed-bottom',
                    ]
                )
            ]);
            case 8:
            case 9:
                return $this->render('process', [
                    'model' => $model,
                    'openid' => $openid,
                    'btnHtml'=> Html::a(
                        '查看维修过程',
                        url::toRoute(['/maintain/fault/detail2','id'=>$model['id']]),
                        [
                            'data-ajax'=>0,
                            'data-status'=>8,
                            'class'=>'process-btn aui-fixed-bottom',
                        ])
                ]);
        }
    }

    /*
     * 维修员提交故障进度，提交资料进度
     * $id 维修表id,
     * 1、更新维修员坐标
     * 2、更维修表的状态
     * 3、写入维修进度+维修时间
     * post 提交 状态  status
     * 确认接单(status=3)
     */
    public function actionProcess($id,$openid)
    {
        set_time_limit(0);
        $post = Yii::$app->request->post('TblServiceProcess');

        $model = TblMachineService::findOne($id);

        if(  $model->status == $post['status'] )
        {
            return $this->render('//tips/home-status',[
                'tips'=>'请不要重复提交！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['/wechat/index','id'=>$model['weixin_id']])
            ]);
        }

        $model->status = $post['status'];
        // 确认接单时间记录, 计数距离  latitude、longitude
        if($model->openid && $model->openid != $openid)           // 任务已重新分配给其他维修员，接单失败！
        {
            return $this->render('//tips/home-status',[
                'tips'=>'任务已重新分配给其他维修员！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['/wechat/index','id'=>$model['weixin_id']])
            ]);
        }
        $model->setKm($post['latitude'],$post['longitude']);


        $model->openid = $openid;
        $wid = $model->weixin_id;
        $fault_id = $model->id;
        $fromOpenid = $model->from_openid;
        $applyTime = $model->add_time;
        $respKm = $model->resp_km;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->save();

            // 主动接单、电话维修、维修员确认接单
            $maintainer = (new \yii\db\Query())
                ->select('name,phone')
                ->from('tbl_user_maintain')
                ->where('wx_id=:wid and openid=:openid',[':wid'=>$wid,':openid'=>$openid])
                ->one();
            $tpl = new WxTemplate($wid);
            $tpl->sendProcess(
                $fromOpenid,
                url::toRoute(['/maintain/fault/detail','id'=>$wid,'fault_id'=>$fault_id],'http'),
                '维修员：'.$maintainer['name'].'已接单，手机：'.$maintainer['phone'].'，距离：'.$respKm.'公里',
                $applyTime
            );

            // 维修进度保存
            $model = new TblServiceProcess();
            $model->service_id = $id;
            $model->process = $post['status'];
            $model->content = $maintainer['name'].' 已接单,距离：'.$respKm.'公里';
            $model->add_time = time();
            $model->save();

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            return $this->render('//tips/home-status',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }

        return $this->render('//tips/home-status',[
            'tips'=>'接单成功',
            'btnText'=>'返回任务中 ...',
            'jumpUrl'=>Url::toRoute(['/maintain/task/list','id'=>$wid]),
            'btnUrl'=> Url::toRoute(['/maintain/task/list','id'=>$wid])
        ]);
    }

    /*
     * 状态进度
     * 2、更维修表的状态
     * 3、写入维修进度+维修时间
     * 到达签到(status=4)、申请配件(6)、配件到达(7)、维修完成(8)、评价完成(9)
     */
    public function actionProcessAjax($id,$openid)
    {
        $status = Yii::$app->request->post('status');

        $model = TblMachineService::findOne($id);
        if(  $model->status ==  $status )
        {
            return $this->render('//tips/home-status',[
                'tips'=>'请不要重复提交！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['/wechat/index','id'=>$model['weixin_id']])
            ]);
        }

        $model->status = $status;
        $wid = $model->weixin_id;
        if($status == 8){
            $model->complete_time = time();     // 维修完成时间
            $model->fault_cost = Yii::$app->request->post('fault_cost')? :0;
            $model->fault_time = $model->complete_time + $model->parts_apply_time -$model->parts_arrive_time - $model->resp_time - $model->accept_time;
        }
        if($model->status == 4) {                               // 记录 确认接单时间
            $model->resp_time = time() - $model->accept_time;
        }

        $fault_id = $model->id;
        $fromOpenid = $model->from_openid;
        $applyTime = $model->add_time;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->save();

            $process = $model->setProcess();
            $model = new TblServiceProcess();
            $model->service_id = $id;
            $model->process = $status;
            $model->content = $process;
            $model->add_time = time();
            $model->save();

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            echo $e;
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'入库失败!']));
        }
        unset($model);

        $res = ['status'=>1,'dataStatus'=>$status+1];
        switch($status){
            case 4:
                $res['href'] = url::toRoute(['/maintain/fault/affirmfault','id'=>$wid,'fault_id'=>$fault_id,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 5:
                $res['href'] = url::toRoute(['/maintain/fault/affirmfault','id'=>$wid,'fault_id'=>$fault_id,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 8:             // 维修完成，为发起维修申请的客户 推送评价提醒
                $tpl = new WxTemplate($wid);
                $tpl->sendWaiting(
                    $fromOpenid,
                    url::toRoute(['/maintain/fault/evaluate','id'=>$wid,'fault_id'=>$id],'http'),
                    time(),
                    $applyTime
                );

                $res['href'] = url::toRoute(['/maintain/fault/detail2','id'=>$id]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
        }
        return json_encode($res);
    }
}
