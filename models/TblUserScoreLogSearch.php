<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblUserScoreLog;

/**
 * TblUserScoreLogSearch represents the model behind the search form about `app\models\TblUserScoreLog`.
 */
class TblUserScoreLogSearch extends TblUserScoreLog
{

    public function rules()
    {
        return [
            [['wx_id', 'score', 'type', 'add_time'], 'integer'],
            [['openid'], 'safe'],
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
        $query = TblUserScoreLog::find()->where(['wx_id'=>Cache::getWid()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wx_id' => $this->wx_id,
            'score' => $this->score,
            'type' => $this->type,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid]);

        return $dataProvider;
    }
}
