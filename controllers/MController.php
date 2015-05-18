<?php

namespace app\controllers;

use app\models\ConfigBase;
use app\models\DataCity;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
use app\models\TblUserMaintain;
use app\models\WxBase;
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
        if( !$model->save())
            throw new BadRequestHttpException('更改状态错误');

        $model = new TblServiceProcess();
        $model->service_id = $id;
        $model->process = $post['status'];
        $model->content = json_encode($post);
        $model->add_time = time();
        if( !$model->save())
            throw new BadRequestHttpException('维修进度错误');

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
        }
        return json_encode($res);


    }
    /*
     * 维修记录
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
            ->orderBy('t.id desc')
            ->all();
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
            ->where(['t.openid' => $openid])
            ->andWhere(['<','t.status',9])
            ->all();
        return $this->render('task',['model'=>$model]);
    }

    /*
     * 维修任务详情
     * 维修申请 id
     */
    public function actionTaskdetail($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id,t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region,m.wx_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.id' => $id,'t.openid'=>$openid])
            ->one();
        if(!$model)
            throw new BadRequestHttpException();
        $region = DataCity::getAddress( $model['region']);

        $status = $model['status'];
        switch($status){
            case 2: return $this->render('taskdetail',['model'=>$model,'region'=>$region,'openid'=>$openid]);
            case 3:
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
     * 提交位置
     */
    public function actionPosition()
    {
        echo '这里获取位置';
    }

    /*
     * 维修员状态转换
     */
    private function maintainStats($status)
    {

    }
}
