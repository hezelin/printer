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
                //if(!file_exists('uploads/')) mkdir('uploads/');
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
        //if($model2 == null) 添加用new

        if ( $model2->load(Yii::$app->request->post()) ) {
            $model2->description = Yii::$app->request->post('SingleImageTextForm')['description'];




//            $model = new UploadForm();
//
//            if (Yii::$app->request->isPost) {
//                $model->file = UploadedFile::getInstance($model, 'file');
//
//                if ($model->file && $model->validate()) {
//                    $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
//                }
//            }
//
//            return $this->render('upload', ['model' => $model]);







            $model2->save();var_dump($_FILES);
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
