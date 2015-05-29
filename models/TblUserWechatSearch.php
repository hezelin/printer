<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblUserWechat;

/**
 * TblUserWechatSearch represents the model behind the search form about `app\models\TblUserWechat`.
 */
class TblUserWechatSearch extends TblUserWechat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_id', 'sex', 'subscribe_time', 'subscribe'], 'integer'],
            [['openid', 'nickname', 'city', 'country', 'province', 'language', 'headimgurl'], 'safe'],
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
        $query = TblUserWechat::find()->where(['wx_id'=>Cache::getWid()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['subscribe_time' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wx_id' => $this->wx_id,
            'sex' => $this->sex,
            'subscribe_time' => $this->subscribe_time,
            'subscribe' => $this->subscribe,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl]);

        return $dataProvider;
    }
}
