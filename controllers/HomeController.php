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

        if (Yii::$app->request->isPost) {
//            $model->image = UploadedFile::getInstance($model, 'image[1]');
//
//            if ($model->image && $model->validate()) {
//                $model->image->saveAs('uploads/' . $model->image->baseName . '.' . $model->image->extension);
//            }
        }
        echo "<span id='aa'>adfsdw</span>";
        echo "<script>alert($('#aa').text())</script>";

        return $this->render('fitment', ['model' => $model]);
    }

    /*
     * 接收上传的图片并返回信息
     */
    public function actionReceiveimage()
    {

        if (Yii::$app->request->isPost) {
            $model = new UploadForm();
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image ) {
                $filename = $model->image->baseName .'.'. $model->image->extension;
                $model->image->saveAs('uploads/' . $filename);
            }

            //返回上传的图片信息并显示
            return '
                { "files": [
                    {
                        "name": "'.$filename.'",
                        "size": '.$model->image->size.',
                        "url": "/uploads/' .$filename.'",
                        "thumbnailUrl": "/uploads/' .$filename.'",
                        "deleteUrl": "carousel/delete",
                        "deleteType": "DELETE"
                    }
                ]}';
        }
    }

    /*
     * 微官网
     */
    public function actionIndex()
    {
        $carousel=Carousel::find()->All();
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
