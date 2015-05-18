<?php
/*
 * 维修申请 service
 */

namespace app\controllers;
use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\TblServiceEvaluate;
use app\models\TblServiceProcess;
use app\models\ToolBase;
use app\models\WxBase;
use app\models\WxJsapi;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class SController extends \yii\web\Controller
{
    public $layout = 'home';
    /*
     * $id 公众号id, $mid 机器id
     * 故障申请
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

        $model = TblMachineService::find()->where(['machine_id'=>$mid])->andWhere(['<','status',9])->one();
        if($model)
            $this->redirect(Url::toRoute(['detail','id'=>$model->id]));

        $openid = WxBase::openId($id);
        return $this->render('apply',['id'=>$id,'openid'=>$openid]);
    }
    /*
     * $id 公众号id, $mid 机器id,$openid 维修员openid
     * 确认故障
     */
    public function actionAffirmfault($id,$mid,$openid)
    {
        if( Yii::$app->request->post() )
        {
            $content = [
                'status'=>5,
                'content'=>$_POST['TblMachineService']['desc']
            ];
            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxBase($id);
                $content['cover'] = $wx->getMedia( $_POST['TblMachineService']['imgid'] );
            }
//            维修进度
            $model = new TblServiceProcess();
            $model->service_id = $mid;
            $model->process = 5;
            $model->content = json_encode($content);
            $model->add_time = time();
            if( !$model->save())
                Yii::$app->end(json_encode(['status'=>0,'msg'=>'维修进度错误']));

            $model = TblMachineService::findOne($mid);
            $model->status = 5;
            if( $model->save() )
                return $this->redirect(Url::toRoute(['m/taskdetail','id'=>$mid]));
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        return $this->render('affirmfault',['id'=>$id,'mid'=>$mid,'openid'=>$openid]);
    }

    /*
     * 维修员 故障确认
     */

    /*
     * 故障进度
     * @params $id 为申请表 id
     */
    public function actionDetail($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id as fault_id,t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,m.id,m.cover,
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

        if($model['status'] == 8)
            $btn = Html::a('评价维修',Url::toRoute(['s/evaluate','id'=>$model['fault_id']]),[
                'class'=>'h-fixed-bottom'
            ]);
        else $btn = '';
        return $this->render('detail',['model'=>$model,'process'=>$process,'btn'=>$btn]);
    }

    /*
     * 客户评价维修
     * $id 维修表id
     */
    public function actionEvaluate($id)
    {
        if(Yii::$app->request->post()){

            $model = new TblServiceEvaluate();
            $model->add_time = time();
            if($model->load(Yii::$app->request->post()) && $model->save())
            {
                $model = TblMachineService::findOne($id);
                $model->status = 9;
                if( !$model->save())
                    Yii::$app->end(json_encode(['status'=>0,'msg'=>'更改状态错误']));

                $model = new TblServiceProcess();
                $model->service_id = $id;
                $model->process = 9;
                $model->content = json_encode(['status'=>9]);
                $model->add_time = time();
                if( !$model->save())
                    Yii::$app->end(json_encode(['status'=>0,'msg'=>'维修进度错误']));

                return $this->render('//tips/homestatus',[
                    'tips'=>'感谢您的评价',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-2)'
                ]);
            }
        }

        return $this->render('evaluate',['id'=>$id]);
    }

    /*
     * 查看用户评价
     * 评价 id
     */
    public function actionShowevaluate($id)
    {
        $model = TblServiceEvaluate::findOne(['fault_id'=>$id]);
        if(!$model)
            throw new BadRequestHttpException('不存在这个评价');
        return $this->render('showevaluate',['model'=>$model]);
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
