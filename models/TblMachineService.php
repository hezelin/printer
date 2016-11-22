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
            [['weixin_id', 'machine_id', 'from_openid', 'add_time'], 'required'],
            [['weixin_id', 'machine_id', 'type', 'status', 'add_time', 'opera_time', 'unfinished_parts_num', 'accept_time', 'resp_time', 'fault_time', 'fault_score', 'parts_apply_time', 'parts_arrive_time', 'complete_time'], 'integer'],
            [['fault_cost', 'resp_km'], 'number'],
            [['from_openid', 'openid'], 'string', 'max' => 28],
            [['content'], 'string', 'max' => 600],
            [['desc'], 'string', 'max' => 1000],
//            [['openid'],'required','on'=>'new-call'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'weixin_id' => '微信id',
            'machine_id' => '机器编号',
            'from_openid' => '申请者openid',
            'openid' => '维修员id',
            'type' => '故障类型',
            'status' => '状态',
            'cover' => '封面',
            'desc' => '故障描述',
            'add_time' => '添加时间',
            'fault_cost' => '维修费用',
            'remark' => '备注',
            'opera_time' => '操作时间',
            'unfinished_parts_num' => '未完成配件数量',
            'accept_time' => '确认接单时间',
            'resp_time' => '反应时间',
            'fault_time' => '维修时长',
            'resp_km' => '反应距离',
            'fault_score' => '评分',
            'parts_apply_time' => '配件申请时间',
            'parts_arrive_time' => '配件到达时间',
            'complete_time' => '维修完成时间',
        ];
    }

    /*
     * 更改机器维修数量
     */
    public function updateMachineCount($type='complete')
    {
        $machine = TblMachine::findOne($this->machine_id);
        if(!$machine)
            return true;
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
        if(isset($result[1]))
            $this->resp_km = substr($result[1],37);
        else
            $this->resp_km = 0;
        return $this->resp_km;
    }

    /*
     * 维修进度 中文描述
     * 到达签到(status=4)、申请配件(6)、配件到达(7)、维修完成(8)、评价完成(9)
     */
    public function setProcess()
    {
        switch($this->status){
            case 4: return '维修员已到达，反应时间：'.$this->duration($this->resp_time);
            case 6: return '申请配件';
            case 7: return '配件到达';
            case 8: return '维修完成，维修时间：'.$this->duration($this->fault_time);
            case 9: return '评价完成';
        }
    }

    /*
     * 计算时间差
     */
    private function duration($t)
    {
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v) {
            $c = number_format($t/(int)$k,2,'.','');
            if (0 != floor($c)) {
                return $c.$v;
            }
        }
    }
}
