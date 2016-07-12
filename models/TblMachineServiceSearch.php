<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachineService;

/**
 * TblMachineServiceSearch represents the model behind the search form about `app\models\TblMachineService`.
 */
class TblMachineServiceSearch extends TblMachineService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'weixin_id', 'machine_id', 'type', 'status', 'unfinished_parts_num', 'add_time', 'opera_time', 'accept_time', 'resp_time', 'fault_time', 'fault_score', 'parts_apply_time', 'parts_arrive_time', 'complete_time'], 'integer'],
            [['from_openid', 'openid', 'content', 'desc', 'enable'], 'safe'],
            [['resp_km'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblMachineService::find()->joinWith([
            'machine'])->where([
            'tbl_machine_service.enable'=>'Y',
            'tbl_machine_service.status'=>1,
            'tbl_machine_service.weixin_id'=>Cache::getWid()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
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
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
