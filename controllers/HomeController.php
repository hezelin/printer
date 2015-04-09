<?php
namespace app\controllers;
use Yii;
use app\models\Carousel;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\StoreSettingForm;
use app\models\SingleImageTextForm;
use yii\web\NotFoundHttpException;

class HomeController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 店铺装修后台
     */
    public function actionFitment()
    {
        $model = new UploadForm();

        if(!isset(Yii::$app->session['wechat'])) $this->redirect('/weixin/index');
        $weixinid = Yii::$app->session['wechat']['id'];
        $carousel=Carousel::find()->where(['show' => 1,'weixinid' => $weixinid])->all();
        return $this->render('fitment', ['model' => $model,'carousel' => $carousel]);
//>>> 82c0bb31bed8c25bcf6874f1ab328b1494b838c9
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
                    return '{"files": [
                        {
                            "name": "'.$model->image->baseName.$model->image->extension.'",
                            "size": ' . $model->image->size . ',
                            "error": "请上传图片文件！"
                        }
                    ]}';
                }
                //空文件
                if($model->image->size <= 0) {
                    return '{"files": [
                        {
                            "name": "'.$model->image->baseName.$model->image->extension.'",
                            "size": ' . $model->image->size . ',
                            "error": "请勿上传空文件！"
                        }
                    ]}';
                }

                //上传
                $filename = time().'_'.rand(100,999).'.'. $model->image->extension;  //原名$model->image->baseName

                $yearmonthdir = date('Ym').'/';
                if(!file_exists('uploads/'.$yearmonthdir)) mkdir('uploads/'.$yearmonthdir);
                $filepath = 'uploads/'.$yearmonthdir.$filename;
                $model->image->saveAs($filepath);

                $newcarousel = new Carousel();
                $newcarousel->weixinid = Yii::$app->session['wechat']['id'];
                $newcarousel->imgurl = $filepath;
                $newcarousel->link = '';
                $newcarousel->title = '默认标题';
                $newcarousel->show = 1;
                $newcarousel->save();
                $newid = $newcarousel->attributes['id'];

                if($newid) {
                    //返回上传的图片信息并显示
//                $returnarr = [
//                    'files' => [
//                        'name' => $filename,
//                        'size' => $model->image->size,
//                        'url' => '/'.$filepath,
//                        'thumbnailUrl' => '/'.$filepath,
//                        'deleteUrl' => 'home/delimg?imagename='.$filepath,
//                        'deleteType' => 'DELETE'
//                    ]
//                ];
//                return json_encode($returnarr);   //报空文件错

                    return '{ "files": [
                        {
                            "id":"' . $newid . '",
                            "name": "' . $filename . '",
                            "size": ' . $model->image->size . ',
                            "url" : "/' . $filepath . '",
                            "thumbnailUrl": "/' . $filepath . '",
                            "deleteUrl": "home/delimg?imagename=' . $filepath . '",
                            "deleteType": "DELETE"
                        }
                    ]}';
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
//        return '{"files": [
//
//  {
//      "' .$imagename.'": true
//  }
//]}';
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
     * 微官网
     */
    public function actionIndex()
    {
        $carousel=Carousel::find()->where(['show'=>1,'weixinid'=>1])->all();
        return $this->renderPartial('index',['carousel'=>$carousel]);
    }

    /*
     * 维修员官网
     */
    public function actionMaintain()
    {
        return $this->render('maintain');
    }

    /*
     * 官网设置
     */
    public function actionSetting()
    {
        if(!isset(Yii::$app->session['wechat'])) $this->redirect('/weixin/index');

        $model = StoreSettingForm::find()->where(['wx_id' => Yii::$app->session['wechat']['id']])->one();
        //若不存在，应执行添加..(添加weixin表时应添加setting表)
        if($model == null) throw new NotFoundHttpException('查看的页面不存在');

        if ( $model->load(Yii::$app->request->post()) ) {
            $model->save();
        }

        $model2 = SingleImageTextForm::find()->where(['wx_id' => Yii::$app->session['wechat']['id']])->one();
        if($model2 == null){   //第一次进入添加，或在添加公众号时添加
            $model2 = new SingleImageTextForm();
            $model2->wx_id = Yii::$app->session['wechat']['id'];
            $model2->keyword = '微官网';
            $model2->matchmode = 1;
            $model2->title = '微官网首页';
            $model2->description = '点击前往微官网首页';
            $model2->imageurl = 'images/home.jpg';
            $model2->status = '1';
            $model2->save();
            $this->refresh();
        }

        if ( $model2->load(Yii::$app->request->post()) ) {
            $model2->description = Yii::$app->request->post('SingleImageTextForm')['description'];
            $model2->status = Yii::$app->request->post('SingleImageTextForm')['status'];

            //封面图上传处理
            $model2->imagefile = UploadedFile::getInstance($model2, 'imagefile');
            if ($model2->imagefile) {    //更换了封面图
                $filename = time().'_'.rand(100,999).'.'. $model2->imagefile->extension;
                $yearmonthdir = date('Ym').'/';
                if(!file_exists('uploads/'.$yearmonthdir)) mkdir('uploads/'.$yearmonthdir);
                $filepath = 'uploads/'.$yearmonthdir.$filename;
                $model2->imagefile->saveAs($filepath);
                $model2->imageurl = $filepath;
            }
            if(!is_file($model2->imageurl)) $model2->imageurl = 'images/home.jpg';  //默认图片

            $model2->imagefile = null;   //没有清空则不能直接使用save()
            $model2->save();
        }

        return $this->render('setting',['model' => $model, 'model2' => $model2]);
    }

    /*
     * 样式选择
     */
    public function actionStyle()
    {
        return $this->render('style');
    }

}
