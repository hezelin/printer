<?php
/*
 * 维修员处理机器
 */
namespace app\modules\maintain\controllers;

use yii\web\Controller;
use app\models\TblRentApply;
use Yii;


class MachineController extends Controller
{
    public $layout = '/auicss';

    /*
     * 提交机器位置
     * $mid 机器id
     */
    public function actionPosition($mid)
    {
        if(Yii::$app->request->post()){
            $model = TblRentApply::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->one();
            $model->load(Yii::$app->request->post());

            if( $model->save() ) {

                return $this->render('//tips/home-status', [
                    'tips' => '机器位置录入成功！',
                    'btnUrl' => 'javascript:history.go(-1)',
                    'btnText' => '返回扫描页',
                ]);
            }
            else echo '出现未知错误！';
        }
    }

}
