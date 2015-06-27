<?php

namespace app\models;

use Yii;

class TblMachineService extends \yii\db\ActiveRecord
{
    public function getMachine()
    {
        return $this->hasOne(TblMachine::className(), ['id' => 'machine_id']);
    }

    public static function tableName()
    {
        return 'tbl_machine_service';
    }

    public function rules()
    {
        return [
            [['machine_id', 'from_openid', 'desc', 'add_time'], 'required'],
            [['machine_id', 'type', 'status', 'unfinished_parts_num', 'add_time', 'parts_apply_time', 'parts_arrive_time', 'complete_time'], 'integer'],
            [['enable'], 'string'],
            [['from_openid', 'openid'], 'string', 'max' => 28],
            [['cover'], 'string', 'max' => 500],
            [['desc'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'machine_id' => '机器id',
            'from_openid' => '申请者openid',
            'openid' => '维修员id',
            'type' => '故障类型',
            'status' => '状态',
            'cover' => '封面',
            'desc' => '故障描述',
            'unfinished_parts_num' => '未完成配件数量',
            'add_time' => '添加时间',
            'parts_apply_time' => '配件申请时间',
            'parts_arrive_time' => '配件到达时间',
            'complete_time' => '维修完成时间',
            'enable' => '是否使用',
        ];
    }

    /*
     * 更改机器维修数量
     */
    public function updateMachineCount($type='complete')
    {
        $machine = TblMachine::findOne($this->machine_id);

        if($type == 'complete'){
            $machine->maintain_count = $machine->maintain_count + 1;
            $machine->status = 2;
        }else{
            $machine->maintain_count = $machine->maintain_count - 1;
            $machine->status = 1;
        }
        return $machine->save();
    }
}
