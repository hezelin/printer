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
            [['weixin_id', 'machine_id', 'from_openid', 'desc', 'add_time'], 'required'],
            [['weixin_id', 'machine_id', 'type', 'status', 'unfinished_parts_num', 'add_time', 'opera_time', 'accept_time', 'resp_time', 'fault_time', 'fault_score', 'parts_apply_time', 'parts_arrive_time', 'complete_time'], 'integer'],
            [['resp_km'], 'number'],
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
            'weixin_id' => '微信id',
            'machine_id' => '机器id',
            'from_openid' => '申请者openid',
            'openid' => '维修员id',
            'type' => '故障类型',
            'status' => '状态',
            'cover' => '封面',
            'desc' => '故障描述',
            'unfinished_parts_num' => '未完成配件数量',
            'add_time' => '添加时间',
            'opera_time' => '操作时间',
            'accept_time' => '确认接单时间',
            'resp_time' => '反应时间',
            'resp_km' => '反应距离',
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

    /*
     * 计算距离
     */
    public function setKm($lat,$long)
    {
        $this->accept_time = time();
        $data = (new \yii\db\Query())
            ->select('latitude,longitude')
            ->from('tbl_rent_apply')
            ->where('machine_id=:mid',[':mid'=>$this->machine_id])
            ->one();
        if(!$data['latitude'] || !$data['longitude'] )
            return false;
        $baseUrl = 'http://map.qq.com/m/index/nav/';
        $params = [
            'cond'=>1,
            'type'=>'drive',
            'sword'=>'我的地点',
            'eword'=>'维修点',
            'spointx'=>$long,
            'spointy'=>$lat,
            'epointy'=>$data['latitude'],
            'epointx'=>$data['longitude']
        ];

        $str = file_get_contents($baseUrl.http_build_query($params));
        preg_match('/result-title\\\">(.+)公里/',$str,$result);
        $this->resp_km = substr($result[1],37);
    }
}
