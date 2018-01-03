<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Container;

class ContainerSearch extends Container
{
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Container::find();

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

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
