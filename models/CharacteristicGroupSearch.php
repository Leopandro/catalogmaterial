<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CharacteristicGroup;

/**
 * CharacteristicGroupSearch represents the model behind the search form about `app\models\CharacteristicGroup`.
 */
class CharacteristicGroupSearch extends CharacteristicGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_value', 'is_required', 'id_group', 'is_visible'], 'integer'],
            [['name', 'label'], 'safe'],
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
        $query = CharacteristicGroup::find();

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
            'id_group' => $this->id_group,
            'is_visible' => $this->is_visible,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
