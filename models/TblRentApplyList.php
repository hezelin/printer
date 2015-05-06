<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblRentApply;

/**
 * TblRentApplySearch represents the model behind the search form about `app\models\TblRentApply`.
 */
class TblRentApplyList extends TblRentApply
{
    /*
     * 关联查询 机器的 品牌、型号、编号3个属性
     */
    public $brand;
    public $type;
    public $serial_id;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_id', 'region', 'due_time', 'status', 'add_time'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'enable', 'apply_word','brand','type','serial_id'], 'safe'],
            [['monthly_rent'], 'number'],
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
        $query = TblRentApply::find()->where([
            'tbl_rent_apply.wx_id'=>Cache::getWid(),
            'tbl_rent_apply.status'=>2,
            'tbl_rent_apply.enable'=>'Y'
        ])->joinWith('machine');

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
            'wx_id' => $this->wx_id,
            'machine_id' => $this->machine_id,
            'region' => $this->region,
            'due_time' => $this->due_time,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'monthly_rent' => $this->monthly_rent,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'apply_word', $this->apply_word])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'serial_id', $this->serial_id]);

        return $dataProvider;
    }
}
