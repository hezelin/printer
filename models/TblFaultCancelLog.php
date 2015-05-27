<?php

namespace app\models;

use Yii;

class TblFaultCancelLog extends \yii\db\ActiveRecord
{
    /*
     * 获取故障
     */
    public function getFault()
    {
        return $this->hasOne(TblMachineService::className(), ['id' => 'service_id']);
    }

    public static function tableName()
    {
        return 'tbl_fault_cancel_log';
    }

    public function rules()
    {
        return [
            [['wx_id', 'service_id', 'opera', 'add_time'], 'required'],
            [['wx_id', 'service_id', 'type', 'add_time'], 'integer'],
            [['opera'], 'string', 'max' => 28],
            [['reason'], 'string', 'max' => 500]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'wx_id' => '公众号id',
            'service_id' => '维修id',
            'opera' => '操作者',
            'type' => '系统/用户',
            'add_time' => '添加时间',
            'reason' => '取消原因',
        ];
    }
}
