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
    public $type;
    public $series_id;
    public $come_from;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'project_id', 'machine_id', 'due_time', 'status', 'add_time','first_rent_time', 'rent_period'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'apply_word', 'enable','type','series_id','come_from'], 'safe'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude', 'accuracy'], 'number'],
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
            'tbl_rent_apply.enable'=>'Y',
        ])->joinWith([
            'machine'=>function($query){
                $query->joinWith([
                    'machineModel'=>function($query){
                        $query->joinWith('brand');
                    },
                ]);
            },
            'machineFault2'
        ]);

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
            'wx_id' => $this->wx_id,
            'project_id' => $this->project_id,
            'machine_id' => $this->machine_id,
            'monthly_rent' => $this->monthly_rent,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'due_time' => $this->due_time,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
            'add_time' => $this->add_time,
            'tbl_machine.come_from' => $this->come_from,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'tbl_rent_apply.name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'apply_word', $this->apply_word])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'tbl_machine_model.type', $this->type])
            ->andFilterWhere(['like', 'tbl_machine.series_id', $this->series_id]);

        return $dataProvider;
    }
}
