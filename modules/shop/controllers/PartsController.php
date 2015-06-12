<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use yii\web\Controller;

class PartsController extends Controller
{
    public $layout = 'shop';
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 配件列表
     * $id 公众号id
     *  [id] => 5
            [wx_id] => 1
            [category_id] => 5
            [name] => 打印纸
            [cover] => /uploads/cover/1506/06/s/psHEGBQ557297fee7391.jpg
            [cover_images] => ["/uploads/cover/1506/06/s/psHEGBQ557297fee7391.jpg","/uploads/cover/1506/06/s/F3CskAT5572980c9d58a.jpg"]
            [market_price] => 20
            [price] => 17
            [amount] => 100
            [describe] =>
     */
    public function actionList($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,c.name as category,t.name,t.cover,t.price')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.wx_id=:wid',[':wid'=>$id])
            ->all();
        return $this->render('list',['model'=>$model]);
    }
}
