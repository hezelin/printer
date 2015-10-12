<?php

namespace app\modules\shop\models;

use app\models\WxBase;
use Yii;

class TblPartsLog extends \yii\db\ActiveRecord
{
    /*
     * status  1 申请中 ,2 携带中 ,3 发送中 ,4 已到达 ,10 已绑定 ,11 已取消 ,12 已回收 ,13 备注
     */
    public static function tableName()
    {
        return 'tbl_parts_log';
    }

    public function rules()
    {
        return [
            [['un', 'wx_id', 'item_id', 'content', 'status', 'add_time'], 'required'],
            [['wx_id', 'item_id', 'status', 'add_time'], 'integer'],
            [['un'], 'string', 'max' => 13],
            [['content'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'un' => 'uniqid',
            'wx_id' => '微信id',
            'item_id' => '类目id',
            'content' => '内容',
            'status' => '状态',
            'add_time' => '添加时间',
        ];
    }

    /*
     * 保存备注
     */
    public function remark($isAdmin=false)
    {
        if($isAdmin)
            $name = (new \yii\db\Query())
                ->select('name')
                ->from('tbl_user_maintain')
                ->where([
                    'openid'=>Yii::$app->request->get('openid'),
                    'wx_id'=>Yii::$app->request->get('id')])
                ->scalar();
        else
            $name = '管理员';

        $content = Yii::$app->request->post('content');

        $this->content = json_encode(['text'=>"$name 备注：$content"],JSON_UNESCAPED_UNICODE);
        $this->status = 13;
        $this->un = Yii::$app->request->get('un');
        $this->item_id = Yii::$app->request->get('item_id');
        $this->wx_id = Yii::$app->request->get('id');
        $this->add_time = time();
        return $this->save();
    }
}
