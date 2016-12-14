<?php

namespace app\modules\maintain\controllers;

use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use Yii;


class RentController extends Controller
{
    public $layout = '/auicss';

    /*
     * 机器没有用户资料，绑定用户资料
     */
    public function actionBind($id,$machine_id)
    {
        $model = TblRentApply::find()
            ->where(['wx_id'=>$id,'machine_id'=>$machine_id])
            ->andWhere(['<','status',11])
            ->one();
        $model || $model = new TblRentApply();

        $openid = WxBase::openId($id);

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->wx_id = $id;
            $model->project_id = 0;
            $model->machine_id = $machine_id;
            $model->add_time = time();
            $model->due_time = time();
            $model->openid = $openid;
            $model->status = 3;                 // 预设机器 ，租赁状态

            if ($model->save()) {
                $model->updateMachineStatus();

                return $this->render('//tips/home-status', [
                    'tips' => '资料录入成功！',
                    'btnText' => '返回主页',
                    'btnUrl' => Url::toRoute(['/wechat/index', 'id' => $id]),
                    'jumpUrl' => Url::toRoute(['/wechat/index', 'id' => $id]),
                ]);
            }
            else
                throw new HttpException(401,ToolBase::arrayToString($model->errors));
        }

        return $this->render('bind',['model'=>$model,'wx_id'=>$id]);
    }
}
