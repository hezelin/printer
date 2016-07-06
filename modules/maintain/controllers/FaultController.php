<?php
/*
 * 路由 s 开头，
 * 故障处理
 */
namespace app\modules\maintain\controllers;

use yii\web\Controller;
use app\models\TblMachineService;
use app\models\TblServiceProcess;
use yii\helpers\Url;
use Yii;


class FaultController extends Controller
{
    public $layout = '/auicss';

    /*
     * 收费维修，维修员输入付款金额
     */
    public function actionCharge($id,$openid)
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

        return $this->render('Charge');
    }

}
