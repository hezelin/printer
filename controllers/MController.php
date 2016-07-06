<?php

namespace app\controllers;

use app\models\ConfigBase;
use app\models\fault\FaultList;
use app\models\TblMachineService;
use app\models\TblNotifyLog;
use app\models\TblRentApply;
use app\models\TblServiceProcess;
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
            return $this->render('//tips/homestatus',[
                'tips'=>'请不要重复提交！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['wechat/index','id'=>$model['weixin_id']])
            ]);
        }

        $model->status = $post['status'];
        // 确认接单时间记录, 计数距离  latitude、longitude
        if($model->openid && $model->openid != $openid)           // 任务已重新分配给其他维修员，接单失败！
        {
            return $this->render('//tips/homestatus',[
                'tips'=>'任务已重新分配给其他维修员！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['wechat/index','id'=>$model['weixin_id']])
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
                Url::toRoute(['s/detail','id'=>$wid,'fault_id'=>$fault_id],'http'),
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
            return $this->render('//tips/homestatus',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }

        return $this->render('//tips/homestatus',[
            'tips'=>'接单成功',
            'btnText'=>'返回任务中 ...',
            'jumpUrl'=>Url::toRoute(['m/task','id'=>$wid]),
            'btnUrl'=> Url::toRoute(['m/task','id'=>$wid])
        ]);
    }

    /*
     * 状态进度
     * 2、更维修表的状态
     * 3、写入维修进度+维修时间
     * 到达签到(status=4)、申请配件(6)、配件到达(7)、维修完成(8)、评价完成(9)
     */
    public function actionProcessajax($id,$openid)
    {
        $status = Yii::$app->request->post('status');

        $model = TblMachineService::findOne($id);
        if(  $model->status ==  $status )
        {
            return $this->render('//tips/homestatus',[
                'tips'=>'请不要重复提交！',
                'btnText'=>'返回主页',
                'btnUrl'=> Url::toRoute(['wechat/index','id'=>$model['weixin_id']])
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
                $res['href'] = Url::toRoute(['s/affirmfault','id'=>$wid,'fault_id'=>$fault_id,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 5:
                $res['href'] = Url::toRoute(['s/affirmfault','id'=>$wid,'fault_id'=>$fault_id,'openid'=>$openid]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
            case 8:             // 维修完成，为发起维修申请的客户 推送评价提醒
                $tpl = new WxTemplate($wid);
                $tpl->sendWaiting(
                    $fromOpenid,
                    Url::toRoute(['s/evaluate','id'=>$wid,'fault_id'=>$id],'http'),
                    time(),
                    $applyTime
                );

                $res['href'] = Url::toRoute(['s/detail2','id'=>$id]);
                $res['dataAjax'] = 0;
                $res['btnText'] = ConfigBase::getFixMaintainStatus($status);
                break;
        }
        return json_encode($res);
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
     * 维修完成，跳转到提交金额页面
     */
    public function actionFaultMoney($id,$openid)
    {
        if( Yii::$app->request->isPost){

            if( $cost = Yii::$app->request->post('fault_cost') ){
                $model = TblMachineService::findOne($id);
                $model->fault_cost = $cost;
                $model->status = 8;
                $model->complete_time = time();     // 维修完成时间
                $model->fault_time = $model->complete_time + $model->parts_apply_time -$model->parts_arrive_time - $model->resp_time - $model->accept_time;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save();

                    $fault = new TblServiceProcess();
                    $fault->service_id = $id;
                    $fault->process = 8;
                    $fault->content = $model->setProcess();
                    $fault->add_time = time();
                    $fault->save();

                    $transaction->commit();
                } catch(\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error','入库失败!');
                }
                return $this->render('//tips/homestatus',[
                    'tips'=>'维修完成！',
                    'btnText'=>'返回维修列表',
                    'btnText2'=>'返回首页',
                    'btnUrl'=>Url::toRoute(['/m/task','id'=>$model->weixin_id]),
                    'btnUrl2'=>Url::toRoute(['/wechat/index','id'=>$model->weixin_id])
                ]);
            }
            else
                Yii::$app->session->setFlash('error','维修金额不能为空！');
        }

        return $this->render('faultMoney');
    }
}
