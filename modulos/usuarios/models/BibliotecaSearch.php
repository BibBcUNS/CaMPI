<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Biblioteca;

/**
 * BibliotecaSearch represents the model behind the search form of `app\models\Biblioteca`.
 */
class BibliotecaSearch extends Biblioteca
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'habilitar_update'], 'integer'],
            [['nombre', 'prefijo', 'url_campi'], 'safe'],
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
        $query = Biblioteca::find();

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
            'id' => $this->id,
            'habilitar_update' => $this->habilitar_update,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'prefijo', $this->prefijo])
            ->andFilterWhere(['like', 'url_campi', $this->url_campi]);

        return $dataProvider;
    }
}
