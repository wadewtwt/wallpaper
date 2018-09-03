<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Kinds;

class KindsSearch extends Kinds
{
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['type'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Kinds::find();

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

        $query->andFilterWhere([
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
