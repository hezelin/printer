<?php
/*
 * 微信端 商品
 */

namespace app\modules\shop\controllers;

use yii\web\Controller;


class ItemController extends Controller
{
    public $layout = 'shop';

    /*
     * 宝贝列表
     */
    public function actionList($id)
    {
        return $this->render('list');
    }

    /*
     * 商城首页
     */
    public function actionHome($id)
    {
        return $this->render('home');
    }

    /*
     * 关键词搜索
     */
    public function actionQuery($id,$q)
    {

    }
}