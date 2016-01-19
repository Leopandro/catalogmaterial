<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CharacteristicGroupTemp;

/**
 * CharacteristicGroupTempSearch represents the model behind the search form about `app\models\CharacteristicGroupTemp`.
 */
class CharacteristicGroupTempSearch extends CharacteristicGroupTemp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_value', 'is_required', 'is_visible', 'id_user'], 'integer'],
            [['name', 'label', 'name_table'], 'safe'],
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
        $query = CharacteristicGroupTemp::find();

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
            'type_value' => $this->type_value,
            'is_required' => $this->is_required,
            'is_visible' => $this->is_visible,
            'id_user' => $this->id_user,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
