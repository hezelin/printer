<?php
/*
 * 维修申请 service
 */

namespace app\controllers;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use app\models\WxJsapi;
use Yii;
use yii\helpers\Url;

class SController extends \yii\web\Controller
{
    public $layout = 'home';
    /*
     * $id 公众号id, $mid 机器id
     */
    public function actionApply($id,$mid)
    {
        if( Yii::$app->request->post() )
        {
            $model = new TblMachineService();
            $model->machine_id = $mid;
            $model->add_time = time();

            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxBase($id);
                $model->cover = $wx->getMedia( $_POST['TblMachineService']['imgid'] );
            }


            $model->load(Yii::$app->request->post());


            if( $model->save() )
                $this->redirect(Url::toRoute(['detail','id'=>$id,'mid'=>$mid]));
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }


        $openid = WxBase::openId($id);
        return $this->render('apply',['id'=>$id,'openid'=>$openid]);
    }

    /*
     * 故障进度
     */
    public function actionDetail($id,$mid)
    {
        return $this->render('detail');
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIrecord()
    {
        return $this->render('irecord');
    }

    public function actionMrecord()
    {
        return $this->render('mrecord');
    }

}
