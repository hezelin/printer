<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblRentReport;

/**
 * TblRentReportSearch represents the model behind the search form about `app\models\TblRentReport`.
 */
class TblRentReportSearch extends TblRentReport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_id', 'colour', 'black_white', 'status', 'add_time'], 'integer'],
            [['total_money', 'exceed_money'], 'number'],
            [['sign_img', 'name', 'enable'], 'safe'],
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
        $query = TblRentReport::find()
            ->joinWith([
                'rentApply'=>function($query){
                    $query->joinWith(['machine'=>function($query){
                        $query->joinWith(['machineModel'=>function($query){
                            $query->joinWith('brand');
                        }]);
                    }]);
                }
            ])
            ->where(['tbl_rent_report.wx_id'=>Cache::getWid()]);
//            ->select('tbl_rent_apply.address,tbl_rent_apply.name as apply_name')

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
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
            'machine_id' => $this->machine_id,
            'tbl_rent_report.colour' => $this->colour,
            'tbl_rent_report.black_white' => $this->black_white,
            'tbl_rent_report.total_money' => $this->total_money,
            'tbl_rent_report.exceed_money' => $this->exceed_money,
            'tbl_rent_report.status' => $this->status,
            'tbl_rent_report.add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'sign_img', $this->sign_img])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
