<?php
namespace app\controllers;
use app\models\Cache;
use app\models\common\Debug;
use app\models\TblWexinReply;
use app\models\WxBase;
use yii\data\ActiveDataProvider;
use app\models\ToolBase;
use Yii;
use app\models\Carousel;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\TblStoreSetting;
use yii\web\NotFoundHttpException;

class HomeController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public $layout = 'console';

    /*
     * 更改信用分数 和 信用过期时间，过期时间需要转换格式
     * hasEditable:1
     * editableIndex:0
     * editableKey:2
     * editableAttribute:verify_credit_lose_time
     * ViewServerRank[0][verify_credit_lose_time]:2016年06月24日
     */
    public function actionEditable()
    {
        $model = Carousel::findOne($_POST['editableKey']);
        if(!$model){
            return ['output'=>'','message'=>'数据库错误'];
        }

        if (isset($_POST['hasEditable'])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $value = $_POST['Carousel'][$_POST['editableIndex']][$_POST['editableAttribute']];
            $model->$_POST['editableAttribute'] = $value;
            if($model->save())
                return ['output'=>$value, 'message'=>''];
            return ['output'=>'','message'=>'数据库错误'];
        }
    }

    /*
     * 店铺装修后台
     */
    public function actionFitment()
    {
        $model = new UploadForm();
        $wx_id = Cache::getWid();

        $dataProvider = new ActiveDataProvider([
            'query' => Carousel::find()->where(['weixinid'=>$wx_id]),
            'pagination' => [
                'pageSize' => 15,
            ],
             'sort'=>['defaultOrder'=>['sort' => SORT_ASC]]
        ]);
        return $this->render('fitment', ['model' => $model,'dataProvider' => $dataProvider,'wx_id'=>$wx_id]);
    }

    /*
     * 接收上传的图片并返回信息
     */
    public function actionReceiveImage()
    {
        if (Yii::$app->request->isPost) {
            $model = new UploadForm();
            $model->image = UploadedFile::getInstance($model, 'image');

            if ( $model->image ) {
                //非图片格式
                if(substr($model->image->type,0,5)!='image') {
                    $data = [
                        'files'=>[
                            [
                                'name'=>$model->image->baseName.$model->image->extension,
                                'size'=>$model->image->size,
                                'error'=>'请上传图片文件！'
                            ]
                        ]
                    ];
                    return json_encode($data);
                }
                //空文件
                if($model->image->size <= 0) {

                    $data = [
                        'files'=>[
                            [
                                'name'=>$model->image->baseName.$model->image->extension,
                                'size'=>$model->image->size,
                                'error'=>'请勿上传空文件！'
                            ]
                        ]
                    ];
                    return json_encode($data);
                }



                //上传
                $filename = uniqid().'.'. $model->image->extension;      //原名$model->image->baseName
                $path = '/uploads/carousel/'.date('ym').'/'.date('d').'/';
                $dir = $this->newDir($path,Yii::getAlias('@app/web/'));

                $model->image->saveAs($dir.$filename);

                $newcarousel = new Carousel();
                $newcarousel->weixinid = Yii::$app->request->get('weixinid');
                $newcarousel->imgurl = $path.$filename;
                $newcarousel->link = '';
                $newcarousel->title = '默认标题';
                $newcarousel->save();
                $newid = $newcarousel->attributes['id'];

                if($newid) {
                    $data = [
                        'files'=>[
                            [
                                'id'=>$newid,
                                'name'=>$filename,
                                'size'=>$model->image->size,
                                'url'=> $path.$filename,
                                'thumbnailUrl'=>$path.$filename,
                            ]
                        ]
                    ];
                    return json_encode($data);
                }
            }
        }
    }

    /*
     * 循环嵌套建立目录
     */
    public function newDir($dir,$preDir=''){
        $parts = explode('/', $dir);
        $dir = $preDir;
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir,0755);
        return $dir;
    }

    /*
     * 删除上传的图片
     */
    public function actionDelimg($id)
    {
        $carousel = Carousel::findOne($id);
        $res=$carousel->delete();
        @unlink(Yii::getAlias('@app/web').$carousel['imgurl']);
        return $res;
    }

    /*
     * Ajax批量删除轮播图
     */
    public function actionMuldelcarousel(){
        if (isset($_POST['delids'])) {
            $i = 0;    //成功删除数
            $carousel = Carousel::findAll($_POST['delids']);
            foreach ($carousel as $onecarousel) {
                @unlink($onecarousel['imgurl']);
                $onecarousel->delete();
                $i++;
            }
            return $i;
        }
    }

    /*
     * Ajax快速更改表格字段文本
     */
    public function actionChangetext(){
        if (isset($_GET['val'])) {
            $newval=$_GET['val']=='点击设置'?'':$_GET['val'];
            $carousel = Carousel::findOne($_GET['id']);
            $carousel -> $_GET['field'] = $newval;
            if($carousel -> save())
                $resarr = ['status' => 1 ];
            else $resarr = ['status' => 0, 'error' => 'Changed 0' ];
        }
        else
            $resarr = ['status' => 0, 'error' => 'GET is empty' ];
        return json_encode($resarr);
    }

    /*
     * 官网设置
     */
    public function actionSetting()
    {
        $wid = Cache::getWid();
        $model = TblStoreSetting::find()
            ->where('wx_id=:wid and enable="Y"',[':wid'=> $wid])
            ->one();

        //若不存在，可执行添加..(添加weixin表时应添加setting表)
        if($model == null) throw new NotFoundHttpException('查看的页面不存在');

        if ( Yii::$app->request->post() && $model->load(Yii::$app->request->post()) ) {

            if( $model->oldAttributes['menu_name'] != $model->menu_name ){
                $wechat = new WxBase($wid);
                $wechat->createMenu($model->menu_name);
            }

            if($model->save())
                return $this->render('//tips/success',['tips'=>'资料修改成功']);
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
        }

        return $this->render('setting',['model' => $model,'wid'=>$wid]);
    }

    //[20161207 新增关注回复的消息
    public function actionReplySetting(){
        $model = new TblWexinReply();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $model->wx_id = Cache::getWid();
            $model->add_time = time();

            //Debug::log(ToolBase::arrayToString($model));
            //return;
           // $data = Yii::$app->request->post('TblWexinReply');

  //          $type =
//            $subscribe_reply =  $data['subscribe_reply'];
//            $wx_id = Cache::getWid();
//

//            $rst = Yii::$app->db->createCommand($sql)->execute();


            if($model->saveReply()) {
                return $this->render('//tips/success', [
                    'tips' => '修改保存成功！',
                    'btnText' => '继续修改',
                    'btnUrl' => \yii\helpers\Url::toRoute(['/home/reply-setting'])
                ]);
            }else{
                return $this->render('//tips/error', [
                    'tips' => '修改保存失败！',
                    'btnText' => '重试',
                    'btnUrl' => \yii\helpers\Url::toRoute(['/home/reply-setting'])
                ]);
            }
        }

        return $this->render('reply', ['model' => $model]);
    }
    //20161207]

    /*
     * 样式选择
     */
    public function actionStyle()
    {
        return $this->render('style');
    }


}
