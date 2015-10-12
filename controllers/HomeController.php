<?php
namespace app\controllers;
use app\models\Cache;
use app\models\WxBase;
use yii\helpers\Url;
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
     * 店铺装修后台
     */
    public function actionFitment()
    {
        $model = new UploadForm();
        $wx_id = Cache::getWid();
        $carousel=Carousel::find()->where(['show' => 1,'weixinid' => $wx_id])->all();

        return $this->render('fitment', ['model' => $model,'carousel' => $carousel,'wx_id'=>$wx_id]);
    }

    /*
     * 接收上传的图片并返回信息
     */
    public function actionReceiveimage()
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
                $filename = time().'_'.rand(100,999).'.'. $model->image->extension;  //原名$model->image->baseName

                $yearmonthdir = date('Ym').'/';
                if(!file_exists('uploads/'.$yearmonthdir)) mkdir('uploads/'.$yearmonthdir);
                $filepath = 'uploads/'.$yearmonthdir.$filename;
                $model->image->saveAs($filepath);

                $newcarousel = new Carousel();
                $newcarousel->weixinid = Yii::$app->request->get('weixinid');
                $newcarousel->imgurl = $filepath;
                $newcarousel->link = '';
                $newcarousel->title = '默认标题';
                $newcarousel->show = 1;
                $newcarousel->save();
                $newid = $newcarousel->attributes['id'];

                if($newid) {
                    $data = [
                        'files'=>[
                            [
                                'id'=>$newid,
                                'name'=>$filename,
                                'size'=>$model->image->size,
                                'url'=>'/' . $filepath,
                                'thumbnailUrl'=>'/' . $filepath,
                                'deleteUrl'=> Url::toRoute(['home/delimg','imagename'=>$filepath]),
                                'deleteType'=>'DELETE'
                            ]
                        ]
                    ];
                    return json_encode($data);
                }
            }
        }
    }

    /*
     * 删除上传的图片
     */
    public function actionDelimg($id)
    {
        $carousel = Carousel::findOne($id);
        $res=$carousel->delete();

        @unlink($carousel['imgurl']);
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

    /*
     * 样式选择
     */
    public function actionStyle()
    {
        return $this->render('style');
    }


}
