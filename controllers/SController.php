<?php
/*
 * 维修申请 service
 */

namespace app\controllers;
use app\models\fault\FaultList;
use app\models\TblMachineService;
use app\models\TblServiceProcess;
use app\models\ToolBase;
use app\models\WxBase;
use app\models\WxMedia;
use Yii;
use yii\helpers\Url;

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
            $model = TblMachineService::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->andWhere(['<','status',9])->one();
            if($model){
                return $this->render('//tips/homestatus',[
                    'tips'=>'请不要重新申请维修！',
                    'btnText'=>'返回主页',
                    'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])
                ]);
            }

            $model = new TblMachineService();
            $model->machine_id = $mid;
            $model->weixin_id = $id;
            $model->add_time = time();

            $tmp = ['cover'=>['/images/default_image.png']];
            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxMedia($id);
                $tmp = ['cover'=> $wx->getImages( explode('|',$_POST['TblMachineService']['imgid'])) ];
            }
            // 获取录音
            if( Yii::$app->request->post('voice') ){
                $wx = new WxMedia($id);
                $tmp['voice'] = $wx->getVoice( Yii::$app->request->post('voice') );
                $tmp['voiceLen'] = Yii::$app->request->post('voiceLen');
            }

            $model->content = json_encode($tmp);


            $model->load(Yii::$app->request->post());
            if( $model->save() ){

                return $this->render('//tips/homestatus',[
                    'tips'=>'维修申请成功！',
                    'btnText'=>'正在返回主页...',
                    'jumpUrl'=>Url::toRoute(['/wechat/index','id'=>$id]),
                    'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])
                ]);
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
     * 故障确定
     */
    public function actionAffirmfault($id,$fault_id,$openid)
    {
        if( Yii::$app->request->post() )
        {
            if( $_POST['TblMachineService']['desc'] )
                $content['text'] = '故障已确定,原因'.$_POST['TblMachineService']['desc'];
            else
                $content['text'] = '故障已确定!';

            //  如果上传图片就拉取图片
            if( isset( $_POST['TblMachineService']['imgid'] ) && $_POST['TblMachineService']['imgid'] ){
                $wx = new WxMedia($id);
                $content['cover'] = $wx->getImages( explode('|',$_POST['TblMachineService']['imgid']) );
            }
            // 获取录音
            if( Yii::$app->request->post('voice') ){
                $wx = new WxMedia($id);
                $content['voice'] = $wx->getVoice( Yii::$app->request->post('voice') );
                $content['voiceLen'] = Yii::$app->request->post('voiceLen');
            }

//            维修进度
            $model = new TblServiceProcess();
            $model->service_id = $fault_id;
            $model->process = 5;
            $model->content = json_encode($content,JSON_UNESCAPED_UNICODE);
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
        $data = new FaultList($id);
        return $this->render('detail',['model'=>$data->progress($fault_id),'id'=>$id]);
    }

    /*
     * 维修员查看维修进度
     * $id 维修 id
     */
    public function actionDetail2($id)
    {
        $data = new FaultList($id);
        return $this->render('detail2',['model'=>$data->progress($id,2)]);
    }
    /*
     * 客户评价维修
     * $id 微信id
     * $fault_id 维修表id
     */
    public function actionEvaluate($id,$fault_id)
    {
        $model = TblMachineService::findOne($fault_id);
        if($model->status == 9 )
            return $this->render('//tips/homestatus',[
                'tips'=>'请不要重复评价！',
                'btnText'=>'返回',
                'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])
            ]);

        if($score = Yii::$app->request->post('score')){

            $model->status = 9;
            $model->fault_score = $score;                  //  增加评价数据
            $model->opera_time = time();
            $wx_id = $model->weixin_id;

            if( !$model->save())
                Yii::$app->end(json_encode(['status'=>0,'msg'=>'更改状态错误']));
            $model->updateMachineCount();

            $model = new TblServiceProcess();
            $model->service_id = $fault_id;
            $model->process = 9;
            $model->content = '评价完成';
            $model->add_time = time();
            if( !$model->save())
                Yii::$app->end(json_encode(['status'=>0,'msg'=>'维修进度错误']));

            return $this->render('//tips/homestatus',[
                'tips'=>'感谢您的评价',
                'btnText'=>'返回',
                'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$wx_id])
            ]);
        }


        return $this->render('evaluate',['id'=>$id]);
    }

    /*
     * 查看用户评价
     * 评价 id
     */
    public function actionShowevaluate($id)
    {
        $model = (new \yii\db\Query())
            ->select('fault_score as score,opera_time as add_time')
            ->from('tbl_machine_service')
            ->where('id=:id and status="9"',[':id'=>$id])
            ->one();
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
        $this->layout = 'auicss';
        $model = TblMachineService::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->orderBy('add_time desc')->asArray()->all();
        foreach ($model as $i=>$m) {
            $content = json_decode($m['content'],true);
            $model[$i]['cover'] = $content['cover'][0];
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
