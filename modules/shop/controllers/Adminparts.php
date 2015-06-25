<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\modules\shop\models\TblParts;
use app\models\WxBase;
use app\modules\shop\models\TblProduct;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class AdminpartsController extends Controller
{
    public $layout = 'shop';
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 查询产品列表
     */
    public function actionList($id)
    {
    }

    /*
     * 详情
     */
    public function actionDetail($id,$item_id)
    {
    }

    /*
     * 配件申请
     * $fault_id 可省略，如果存在就绑定维修 tbl_parts_faults ，不存在就绑定到携带表 tbl_parts_bring
     */
    public function actionApply($id,$item_id)
    {
    }

    public function actionTest()
    {
        return $this->render('//tips/homestatus',['tips'=>'配件库存为0','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
    }
}
