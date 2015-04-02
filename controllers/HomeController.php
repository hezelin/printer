<?php

namespace app\controllers;
use Yii;
use app\models\Carousel;
use app\models\UploadForm;
use yii\web\UploadedFile;

class HomeController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 店铺装修后台
     */
    public function actionFitment()
    {
        $model = new UploadForm();
        $carousel0=Carousel::find()->where(['show' => 0])->all();  //未展示
        $carousel=Carousel::find()->where(['show' => 1])->all();   //已展示
        return $this->render('fitment', ['model' => $model,'carousel'=>$carousel,'carousel0'=>$carousel0]);
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
                $newcarousel->imgurl = $filepath;
                $newcarousel->link = '';
                $newcarousel->title = '标题';
                $newid=2;//$newcarousel->save();

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
                        "id":"'.$newid.'",
                        "name": "'.$filename.'",
                        "size": '.$model->image->size.',
                        "url" : "/'.$filepath.'",
                        "thumbnailUrl": "/'.$filepath.'",
                        "deleteUrl": "home/delimg?imagename=' .$filepath.'",
                        "deleteType": "DELETE"
                    }
                ]}';
            }
        }
    }

    /*
     * 删除上传的图片
     */
    public function actionDelimg($id)
    {
        echo 'del'.$id;
//        return '{"files": [
//
//  {
//      "' .$imagename.'": true
//  }
//]}';
    }

    /*
     * 微官网
     */
    public function actionIndex()
    {
        $carousel=Carousel::find()->where(['show' => 1])->all();
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
        return $this->render('setting');
    }

    /*
     * 样式选择
     */
    public function actionStyle()
    {
        return $this->render('style');
    }

}
