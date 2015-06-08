<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachine;

/**
 * TblMachineSearch represents the model behind the search form about `app\models\TblMachine`.
 */
class TblMachineSearch extends TblMachine
{
    public $brand;
    public $type;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'model_id', 'status', 'maintain_count', 'rent_count', 'add_time'], 'integer'],
            [['series_id', 'buy_date', 'else_attr', 'enable','brand','type'], 'safe'],
            [['buy_price'], 'number'],
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
        $query = TblMachine::find()
            ->where(['tbl_machine.wx_id'=>Cache::getWid(),'tbl_machine.enable'=>'Y'])
            ->joinWith('machineModel');
            /*->select('tbl_machine.id,tbl_machine.buy_date,series_id,buy_price,maintain_count,rent_count,
                tbl_machine.status,tbl_machine.add_time,tbl_machine_model.type,
                tbl_brand.name')
            ->joinWith('machineModel');
            ->leftJoin('tbl_machine_model','tbl_machine_model.id=tbl_machine.model_id')
            ->leftJoin('tbl_brand','tbl_machine_model.brand_id=tbl_brand.id');*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'wx_id' => $this->wx_id,
            'model_id' => $this->model_id,
            'buy_date' => $this->buy_date,
            'buy_price' => $this->buy_price,
            'status' => $this->status,
            'maintain_count' => $this->maintain_count,
            'rent_count' => $this->rent_count,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'else_attr', $this->else_attr])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', '`tbl_machine_model`.`type`', $this->type]);

        return $dataProvider;
    }
}
