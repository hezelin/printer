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
use app\models\WxMedia;
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
            $model->weixin_id = $id;
            $model->add_time = time();

            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxMedia($id);
                $model->cover = json_encode( $wx->getImages( explode('|',$_POST['TblMachineService']['imgid'] ) ) );
            }else
                $model->cover = json_encode(['/images/default_image.png']);

            $model->load(Yii::$app->request->post());
            if( $model->save() ){
                $this->redirect(Url::toRoute(['detail','id'=>$id,'fault_id'=>$model->id]));
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        $model = TblMachineService::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->andWhere(['<','status',9])->one();
        if($model)
            $this->redirect(Url::toRoute(['detail','id'=>$id,'fault_id'=>$model->id]));

        $openid = WxBase::openId($id);
        return $this->render('apply',['id'=>$id,'openid'=>$openid]);
    }
    /*
     * $id 公众号id, $mid 维修的 id,$openid 维修员openid
     * 确认故障
     */
    public function actionAffirmfault($id,$fault_id,$openid)
    {
        if( Yii::$app->request->post() )
        {
            $content = [
                'status'=>5,
                'content'=>$_POST['TblMachineService']['desc']
            ];
            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxMedia($id);
                $content['cover'] = $wx->getImages( explode('|',$_POST['TblMachineService']['imgid']) );
            }
//            维修进度
            $model = new TblServiceProcess();
            $model->service_id = $fault_id;
            $model->process = 5;
            $model->content = json_encode($content);
            $model->add_time = time();
            if( !$model->save()){
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
                return $this->render('affirmfault',['id'=>$id,'fault_id'=>$fault_id,'openid'=>$openid]);
            }

            $model = TblMachineService::findOne($fault_id);
            $model->status = 5;
            if( $model->save() )
                return $this->redirect(Url::toRoute(['m/taskdetail','id'=>$fault_id]));
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        return $this->render('affirmfault',['id'=>$id,'fault_id'=>$fault_id,'openid'=>$openid]);
    }

    /*
     * 故障进度
     * $id,公众号id,fault_id,故障id
     * 微信申请发起用户的页面
     * 1、发起评价。 2、取消维修
     */
    public function actionDetail($id,$fault_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id as fault_id,t.cover as fault_cover,t.desc,t.type as fault_type,
                    t.add_time,t.status,m.id,p.cover,
                    b.name as brand,p.type,m.series_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','m.model_id=p.id')
            ->leftJoin('tbl_brand as b','p.brand_id=b.id')
            ->where(['t.id' => $fault_id])
            ->one();

        // 图片预览 路径设置
        $covers = json_decode($model['fault_cover'],true);
        $model['fault_cover'] = Yii::$app->request->hostInfo.$covers[0];
        foreach($covers as $cover)
            $model['cover_images'][] = Yii::$app->request->hostinfo.$cover;

        $process = (new \yii\db\Query())
            ->select('content,add_time')
            ->from('tbl_service_process')
            ->where(['service_id' => $fault_id])
            ->orderBy('id desc')
            ->all();

        if($model['status'] == 8)
            $btn = Html::a('评价维修',Url::toRoute(['s/evaluate','id'=>$model['fault_id']]),[
                'class'=>'h-fixed-bottom'
            ]);
        else $btn = '';

        if($model['status']== 1 || $model['status'] == 2)
            $btn = Html::a('取消维修',Url::toRoute(['s/cancel','id'=>$id,'fid'=>$model['fault_id']]),[
                'class'=>'h-fixed-bottom'
            ]);


        return $this->render('detail',['model'=>$model,'id'=>$id,'process'=>$process,'btn'=>$btn]);
    }

    /*
     * 维修员查看维修进度
     * $id 维修 id
     */
    public function actionDetail2($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id as fault_id,t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,m.id,p.cover,
                    b.name as brand,p.type,m.series_id,m.wx_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','p.id=m.model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
            ->where(['t.id' => $id])
            ->one();

        // 图片预览 路径设置
        $covers = json_decode($model['fault_cover'],true);
        $model['fault_cover'] = Yii::$app->request->hostInfo.$covers[0];
        foreach($covers as $cover)
            $model['cover_images'][] = Yii::$app->request->hostinfo.$cover;

        $process = (new \yii\db\Query())
            ->select('content,add_time')
            ->from('tbl_service_process')
            ->where(['service_id' => $id])
            ->orderBy('id desc')
            ->all();

        return $this->render('detail2',['model'=>$model,'process'=>$process]);
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
                $model->updateMachineCount();
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
            return $this->render('//tips/homestatus',['tips'=>'不存在这个评价']);
        return $this->render('showevaluate',['model'=>$model]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 机器维修记录
     * $id ，公众号id
     * $mid , 机器id
     */
    public function actionIrecord($id,$mid)
    {
        $model = TblMachineService::findAll(['machine_id'=>$mid,'enable'=>'Y']);
        foreach ($model as $i=>$m) {
            $covers = json_decode($m['cover'],true);
            $model[$i]['cover'] = $covers[0];
        }
        return $this->render('irecord',['model'=>$model,'id'=>$id]);
    }

    /*
     * 取消维修
     * $id 公众号id,$fid 维修表id
     */
    public function actionCancel($id,$fid)
    {
        return $this->render('cancel',['id'=>$id,'fid'=>$fid,'openid'=>WxBase::openId($id)]);
    }

    public function actionMrecord()
    {
        return $this->render('mrecord');
    }

    /*
     * 维修进度申请配件
     *
     */
    public function actionApplyparts($id,$fault_id)
    {
        return $this->render('applyparts',[
            'id'=>$id,
            'fault_id'=>$fault_id,
            'mUrl' => Url::toRoute(['/shop/codeapi/parts'],'http'),
        ]);
    }

}
