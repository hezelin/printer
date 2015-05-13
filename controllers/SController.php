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
                $this->redirect(Url::toRoute(['detail','id'=>$model->id]));
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        $model = TblMachineService::findOne(['machine_id'=>$mid]);
        if($model)
            $this->redirect(Url::toRoute(['detail','id'=>$model->id]));

        $openid = WxBase::openId($id);
        return $this->render('apply',['id'=>$id,'openid'=>$openid]);
    }

    /*
     * 故障进度
     * @params $id 为申请表 id
     */
    public function actionDetail($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,m.id,m.cover,
                    m.brand,m.type,m.serial_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->where(['t.id' => $id])
            ->one();
        $process = (new \yii\db\Query())
            ->select('content,add_time')
            ->from('tbl_service_process')
            ->where(['service_id' => $id])
            ->orderBy('id desc')
            ->all();

        return $this->render('detail',['model'=>$model,'process'=>$process]);
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
