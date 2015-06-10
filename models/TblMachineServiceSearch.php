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
            [['id', 'machine_id', 'type', 'status', 'add_time'], 'integer'],
            [['from_openid', 'openid', 'cover', 'desc', 'enable'], 'safe'],
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
            'machine'=>function($query){
                $query->joinWith([
                    'machineModel'=>function($query){
                        $query->joinWith('brand');
                    }
                ]);
        }])->where([
            'tbl_machine_service.enable'=>'Y',
            'tbl_machine_service.status'=>1
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
            'type' => $this->type,
            'status' => $this->status,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'from_openid', $this->from_openid])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
