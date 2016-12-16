<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/6
 * Time: 18:22
 */
namespace app\models;


class TblWexinReply extends \yii\db\ActiveRecord{


    public static function tableName(){
        return 'tbl_wexin_reply';
    }

    /**
     * 保存关注自动回复信息
     *
     */
    public function saveReply($wx_id,$subscribe_reply, $add_time){
        $sql = "INSERT INTO `tbl_wexin_reply`(`wx_id`,`subscribe_reply`, `add_time`) VALUES($wx_id,'".$subscribe_reply."',".$add_time.")  ON DUPLICATE KEY UPDATE `subscribe_reply` = VALUES(subscribe_reply), `add_time` = VALUES(add_time)";

        return \Yii::$app->db->createCommand($sql)->execute();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_id' => '微信id',
            'type' => '类型', //text => 文本类型， news => 图文类型
            'subscribe_reply' => '关注后自动回复的信息',
            'enable' => '是否可用',
            'add_time' => '添加时间',
        ];
    }

    public function rules(){
        return [
            [['wx_id', 'subscribe_reply', 'add_time'], 'required'],
            [['wx_id', 'add_time'], 'integer'],
            [['type', 'enable'], 'string'],
            [['subscribe_reply'], 'string', 'max' => 500],
        ];

    }

}