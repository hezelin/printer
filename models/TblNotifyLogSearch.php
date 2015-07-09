<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Cache;
use yii\data\ActiveDataProvider;
use app\models\TblNotifyLog;

/**
 * TblNotifyLogSearch represents the model behind the search form about `app\models\TblNotifyLog`.
 */
class TblNotifyLogSearch extends TblNotifyLog
{
    public $fromname;
    public function rules()
    {
        return [
            [['id', 'wx_id', 'from_id', 'add_time'], 'integer'],
            [['openid', 'text', 'is_read', 'enable','fromname'], 'safe'],
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
        $query = TblNotifyLog::find()->where(['tbl_notify_log.wx_id'=>Cache::getWid(),'tbl_notify_log.enable'=>'Y'])->joinWith(['userinfo','fromsend']);

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
            'id' => $this->id,
            'wx_id' => $this->wx_id,
            'from_id' => $this->from_id,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'is_read', $this->is_read])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'tbl_user_base.name', $this->fromname]);

        return $dataProvider;
    }
}
