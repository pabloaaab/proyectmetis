<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proceso;

/**
 * ProcesoSearch represents the model behind the search form of `app\models\Proceso`.
 */
class ProcesoSearch extends Proceso
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_proceso'], 'integer'],
            [['proceso'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Proceso::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_proceso' => $this->id_proceso,
        ]);

        $query->andFilterWhere(['like', 'proceso', $this->proceso]);

        return $dataProvider;
    }
}
