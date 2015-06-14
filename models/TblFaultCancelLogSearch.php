<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblFaultCancelLogSearch represents the model behind the search form about `app\models\TblFaultCancelLog`.
 */
class TblFaultCancelLogSearch extends TblFaultCancelLog
{
    /*
     * 增加 3个属性 status,type,desc
     */
    public $status;
    public $desc;
    public $faulttype;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'service_id', 'type', 'add_time'], 'integer'],
            [['opera', 'reason', 'status', 'desc','faulttype'], 'safe'],
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
        $query = TblFaultCancelLog::find()->where(['wx_id'=>Cache::getWid()])->joinWith('fault');

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
            'service_id' => $this->service_id,
            'type' => $this->type,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'opera', $this->opera])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'tbl_machine_service.desc', $this->desc])
            ->andFilterWhere(['like', 'tbl_machine_service.status', $this->status])
            ->andFilterWhere(['like', 'tbl_machine_service.type', $this->faulttype]);

        return $dataProvider;
    }
}
