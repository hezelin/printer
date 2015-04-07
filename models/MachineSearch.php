<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachine;

/**
 * MachineSearch represents the model behind the search form about `app\models\TblMachine`.
 */
class MachineSearch extends TblMachine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'depreciation', 'add_time', 'rent_time', 'maintain_time', 'status'], 'integer'],
            [['serial_id', 'brand', 'type', 'buy_time', 'remark', 'enable'], 'safe'],
            [['price'], 'number'],
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
        $query = TblMachine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'wx_id' => $this->wx_id,
            'price' => $this->price,
            'depreciation' => $this->depreciation,
            'buy_time' => $this->buy_time,
            'add_time' => $this->add_time,
            'rent_time' => $this->rent_time,
            'maintain_time' => $this->maintain_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'serial_id', $this->serial_id])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
