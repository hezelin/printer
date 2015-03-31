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
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
            }
        }

        return $this->render('fitment', ['model' => $model]);
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
