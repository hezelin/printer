<?php

namespace app\controllers;

use app\models\ConfigBase;
use app\models\DataCity;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\WxBase;
use app\models\WxTemplate;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

class MController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public $layout = 'home';
    /*
     * 我的评价
     */
    public function actionEvaluate()
    {
        return $this->render('evaluate');
    }

    /*
     * 我的业绩，统计数据
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 最新通知
     */
    public function actionNotice()
    {
        return $this->render('notice');
    }

    /*
     * 维修员提交故障进度，提交资料进度
     * $id 维修表id,
     * 2、更维修表的状态
     * 3、写入维修进度+维修时间
     * 1、更新维修员坐标
     * post 提交 状态  status
     */
    public function actionProcess($id,$openid)
    {
        $post = Yii::$app->request->post('TblServiceProcess');
        $model = TblUserMaintain::findOne(['openid'=>$openid]);
        $model->attributes = $post;
        $wid = $model->wx_id;
        if( !$model->save())
            throw new BadRequestHttpException('数据不合法');

        $model = TblMachineService::findOne($id);
        $model->status = $post['status'];
        $model->openid = $openid;
        $rendId = $model->id;
        $fromOpenid = $model->from_openid;
        $applyTime = $model->add_time;
        if( !$model->save())
            throw new BadRequestHttpException('更改状态错误');

        $model = new TblServiceProcess();
        $model->service_id = $id;
        $model->process = $post['status'];
        $model->content = json_encode($post);
        $model->add_time = time();
        if( !$model->save())
            throw new BadRequestHttpException('维修进度错误');

        $from = Yii::$app->request->post('from');

        if($from == 'initiative'){              // 主动接单，给用户发送推送
            $tpl = new WxTemplate($wid);
            $tpl->sendProcess(
                $fromOpenid,
                Url::toRoute(['s/detail','id'=>$rendId],'http'),
                '维修员已接单',
                $applyTime
            );
        }

        return $this->render('//tips/homestatus',[
            'tips'=>'接单成功',
            'btnText'=>'返回',
            'btnUrl'=> Url::toRoute(['m/task','id'=>$wid])
        ]);
    }

    /*
     * 状态进度
     * 2、更维修表的状态
     * 3、写入维修进度+维修时间
     * status = 2,3,4,5,6,7,9
     */
    public function actionProcessajax($id,$openid)
    {
        $status = Yii::$app->request->post('status');

        $model = TblMachineService::findOne($id);
        $model->status = $status;
        if( !$model->save())
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'更改状态错误']));
        $mid = $model->machine_id;
        $fromOpenid = $model->from_openid;
        $applyTime = $model->add_time;

        $model = new TblServiceProcess();
        $model->service_id = $id;
        $model->process = $status;
        $model->content = json_encode(['status'=>$status]);
        $model->add_time = time();
        if( !$model->save())
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'维修进度错误']));

        $wid = TblRentApply::find()->select('wx_id')->where(['machine_id'=>$mid,'enable'=>'Y'])->scalar();

        $res = ['status'=>1,'dataStatus'=>$status+1];
        switch($status){
            case 4:
                $res['href'] = Url::toRoute(['s/affirmfault','id'=>$wid,'mid'=>$mid,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 5:
                $res['href'] = Url::toRoute(['s/affirmfault','id'=>$wid,'mid'=>$mid,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 8:             // 维修完成，为发起维修申请的客户 推送评价提醒
                $tpl = new WxTemplate($wid);
                $tpl->sendWaiting(
                    $fromOpenid,
                    Url::toRoute(['s/evaluate','id'=>$id],'http'),
                    time(),
                    $applyTime
                );

                $res['href'] = Url::toRoute(['s/detail','id'=>$wid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
        }
        return json_encode($res);


    }
    /*
     * 维修记录
     * $id 为公众号id
     */
    public function actionRecord($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id, t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid])
            ->andWhere(['t.enable' => 'Y'])
            ->orderBy('t.id desc')
            ->all();

        foreach ($model as $i=>$m) {
            $covers = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $covers[0];
        }

        return $this->render('record',['model'=>$model]);
    }

    /*
     * 最新任务
     * $id 为公众号id
     */
    public function actionTask($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id, t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid,'t.enable' => 'Y'])
            ->andWhere(['<','t.status',9])
            ->all();

        foreach ($model as $i=>$m) {
            $covers = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $covers[0];
        }


        return $this->render('task',['model'=>$model]);
    }

    /*
     * 维修任务详情
     * 维修申请 id
     */
    public function actionTaskdetail($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    t.machine_id as mid, m.address,m.name,m.phone,m.region,m.wx_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.id' => $id])
            ->one();
        if(!$model)
            throw new BadRequestHttpException();

        // 图片预览 路径设置
        $covers = json_decode($model['fault_cover'],true);
        $model['fault_cover'] = Yii::$app->request->hostInfo.$covers[0];
        foreach($covers as $cover)
            $model['cover_images'][] = Yii::$app->request->hostinfo.$cover;

        $openid = WxBase::openId($model['wx_id']);
        $region = DataCity::getAddress( $model['region']);

        $status = $model['status'];
        switch($status){
            case 1: return $this->render('taskdetail',['model'=>$model,'region'=>$region,'openid'=>$openid,'from'=>'initiative']);
            case 2: return $this->render('taskdetail',['model'=>$model,'region'=>$region,'openid'=>$openid,'from'=>'allot']);
            case 3: return $this->render('process3', [
                        'model' => $model,
                        'region' => $region,
                        'openid' => $openid,
                        'mUrl' => Url::toRoute(['codeapi/machine','id'=>$model['mid']],'http'),
                        'btnHtml'=>Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            '#',
                            [
                                'data-ajax'=>1,
                                'data-status'=>$status+1,
                                'class'=>'h-fixed-bottom',
                                'id'=>'process-btn',
                                'data-href'=>Url::toRoute(['m/processajax','id'=>$model['id'],'openid'=>$openid])
                            ]
                        )
                    ]);
            case 6:
            case 7: return $this->render('process', [
                        'model' => $model,
                        'region' => $region,
                        'openid' => $openid,
                        'btnHtml'=>Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            Url::toRoute(['m/processajax','id'=>$model['id'],'openid'=>$openid]),
                            [
                                'data-ajax'=>1,
                                'data-status'=>$status+1,
                                'class'=>'h-fixed-bottom',
                                'id'=>'process-btn'
                            ]
                        )
                    ]);
            case 4: return $this->render('process', [
                        'model' => $model,
                        'region' => $region,
                        'openid' => $openid,
                        'btnHtml'=>Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            Url::toRoute(['s/affirmfault','id'=>$model['wx_id'],'mid'=>$model['id'],'openid'=>$openid]),
                            [
                                'data-ajax'=>0,
                                'data-status'=>$status+1,
                                'class'=>'h-fixed-bottom',
                                'id'=>'process-btn'
                            ]
                        )
                    ]);
            case 5: return $this->render('process', [
                        'model' => $model,
                        'region' => $region,
                        'openid' => $openid,
                        'btnHtml'=>Html::tag(
                            'div',
                            Html::a(
                                '维修完成',
                                Url::toRoute(['m/processajax','id'=>$model['id'],'openid'=>$openid]),
                                [
                                    'data-ajax'=>1,
                                    'data-status'=>8,
                                    'class'=>'h-part h-off-60',
                                    'id'=>'process-btn'
                            ]).
                            Html::a(
                                ConfigBase::getFixMaintainStatus($status),
                                Url::toRoute(['s/applyparts','id'=>$model['id'],'openid'=>$openid]),
                                [
                                    'data-ajax'=>0,
                                    'data-status'=>$status+1,
                                    'class'=>'h-part h-off-40',
                                    'id'=>'process-btn'
                            ]),
                            ['class'=>'h-fixed-bottom']
                        )
                    ]);
            case 8:
            case 9: return $this->render('process', [
                'model' => $model,
                'region' => $region,
                'openid' => $openid,
                'btnHtml'=> Html::tag(
                        'div',
                        Html::a(
                            '查看维修进度',
                            Url::toRoute(['s/detail','id'=>$model['id']]),
                            [
                                'data-ajax'=>0,
                                'data-status'=>8,
                                'class'=>'h-part h-off-60',
                                'id'=>'process-btn'
                        ]).
                        Html::a(
                            '查看评价',
                            Url::toRoute(['s/showevaluate','id'=>$model['id']]),
                            [
                                'data-ajax'=>0,
                                'data-status'=>$status+1,
                                'class'=>'h-part h-off-40',
                                'id'=>'process-btn'
                            ]
                        ),
                        ['class'=>'h-fixed-bottom']
                    )

            ]);
        }


    }

    /*
     * 提交机器位置
     * $mid 机器id
     */
    public function actionMachineposition($mid)
    {
        if(Yii::$app->request->post()){
            $model = TblRentApply::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->one();
            $model->load(Yii::$app->request->post());

            if( $model->save() ) {

                return $this->render('//tips/homestatus', [
                    'tips' => '机器位置录入成功！',
                    'btnUrl' => 'javascript:history.go(-1)',
                    'btnText' => '返回扫描页',
                ]);
            }
            else echo '出现未知错误！';
        }
    }

    /*
     * 主动接单
     * $id公众号id
     */
    public function actionInitiative($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id, t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.enable' => 'Y','t.status' => 1])
            ->orderBy('t.id desc')
            ->all();

        foreach ($model as $i=>$m) {
            $covers = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $covers[0];
        }

        return $this->render('initiative',['model'=>$model,'count'=>count($model)]);
    }
}
