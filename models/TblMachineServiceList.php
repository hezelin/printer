<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachineService;


class TblMachineServiceList extends TblMachineService
{
    public function rules()
    {
        return [
            [['id', 'weixin_id', 'machine_id', 'type', 'status', 'unfinished_parts_num', 'add_time', 'opera_time', 'accept_time', 'resp_time', 'fault_time', 'fault_score', 'parts_apply_time', 'parts_arrive_time', 'complete_time'], 'integer'],
            [['from_openid', 'openid', 'content', 'desc'], 'safe'],
            [['resp_km'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TblMachineService::find()->joinWith([
            'machine'=>function($query){
                $query->joinWith([
                    'machineModel'=>function($query){
                        $query->joinWith('brand');
                    }
                ]);
            }
        ])->where([
//            'tbl_machine_service.enable'=>'Y',
            'tbl_machine_service.weixin_id'=>Cache::getWid()
        ]);

        $process = Yii::$app->request->get('process');
        if($process == 2)               // 等待评价
            $query->andWhere(['tbl_machine_service.status'=>8]);
        elseif($process == 3)           // 完成中
            $query->andWhere(['tbl_machine_service.status'=>9]);
        else                            // 维修中
            $query->andWhere(['<','tbl_machine_service.status',8]);

        if(Yii::$app->request->get('fromFault'))
            $query->andWhere(['tbl_machine_service.openid'=>Yii::$app->request->get('fromFault')]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'machine_id' => $this->machine_id,
            'unfinished_parts_num' => $this->unfinished_parts_num,
            'parts_apply_time' => $this->parts_apply_time,
            'parts_arrive_time' => $this->parts_arrive_time,
            'complete_time' => $this->complete_time,
            'tbl_machine_service.type' => $this->type,
            'tbl_machine_service.status' => $this->status,
            'tbl_machine_service.add_time' => $this->add_time,
            'opera_time' => $this->opera_time
        ]);

        $query->andFilterWhere(['like', 'from_openid', $this->from_openid])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }

    /*
     * 返回维修员
     */
    public function fixProvider()
    {
        //20161228 biao 维修员表：新增状态表
        return new ActiveDataProvider([
            'query' => TblUserMaintain::find()->where(['wx_id'=>Cache::getWid(),'status' => 10]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }
}
