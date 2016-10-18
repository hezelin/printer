<?php

namespace app\modules\shop\controllers;

use app\models\TblShopHomeCarousel;
use app\models\ToolBase;
use yii\web\Controller;
use app\models\Cache;
use Yii;

class AdminHomeController extends Controller
{
    public $layout = '/console';

    /*
     * 商城旋转木马
     */
    public function actionCarousel()
    {
        $wid = Cache::getWid();

        $model = TblShopHomeCarousel::findOne($wid);
        if($model == null)
        {
            $model = new TblShopHomeCarousel();
            $model->wx_id = $wid;
        }

        if ( Yii::$app->request->post() && $model->load(Yii::$app->request->post()) ) {

            if($model->save())
                return $this->render('//tips/success',['tips'=>'资料修改成功']);
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        return $this->render('carousel',[
            'model' => $model,
            'wid'=>$wid
        ]);
    }
}
