<?php

namespace app\modules\shop\controllers;

use app\models\TblProduct;
use yii\web\Controller;
use Yii;
use app\models\Cache;

class BackendController extends Controller
{
    public $layout = '/console';

    /*
     * 配置 ueditor 图片上传路径
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/uploads/product/{yy}{mm}/{dd}/{time}{rand:6}",//上传保存路径
                    "imageCompressBorder" => 640,
                ],
            ]
        ];
    }
    /*
     * 添加宝贝
     */
    public function actionAdd()
    {
        $model = new TblProduct();

        if ($model->load(Yii::$app->request->post())) {

            $wx_id = Cache::getWid();
            $serialId = $model->serial_id? :(Yii::$app->session['wechat']['machine_count'] + 1);

            $initSerialId = $model->serial_id;                      // multisave 用到
            $model->serial_id = (string)$serialId;
            $model->wx_id = $wx_id;
            $model->add_time = time();

            if( !$model->validate() ){                              // 验证数据是否完整
                Yii::$app->session->setFlash('error',\app\models\ToolBase::arrayToString($model->errors) );
                return $this->render('add', ['model' => $model ]);
            }

            if( $model->amount == 1 ){

                if( $model->save() ){
                    $model->updateCount();                          // 更新计数
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else
                    Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors) );
            }else{
                $model->serial_id = $initSerialId;
                $row =$model->multiSave();                          // 批量插入，并且自动更新统计
                return $this->render('//tips/success',['tips'=>'成功添加 '.$row.' 个机器！']);
            }

        }

        return $this->render('add', ['model' => $model]);
    }

    public function actionList()
    {
        return $this->render('list');
    }
}
