<?php
/*
 * 维修员 帮助页面
 */
namespace app\modules\maintain\controllers;

use yii\web\Controller;


class HelpController extends Controller
{
    public $layout = '/auicss';

    /*
     * 主动接单
     * $id公众号id
     */
    public function actionIndex($id)
    {
        return $this->render('index',['id'=>$id]);
    }

    /*
     * 使用帮助
     */
    public function actionUse()
    {
        return $this->render('use');
    }
}
