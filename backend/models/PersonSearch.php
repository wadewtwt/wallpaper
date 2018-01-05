<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Person;

class PersonSearch extends Person
{
    public function rules()
    {
        return [
            [['name', 'cellphone'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Person::find()->andWhere(['status' => Person::STATUS_NORMAL]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cellphone', $this->cellphone]);

        return $dataProvider;
    }
}
