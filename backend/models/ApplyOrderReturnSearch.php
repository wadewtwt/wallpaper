<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\ApplyOrderReturn;

class ApplyOrderReturnSearch extends ApplyOrderReturn
{
    public function rules()
    {
        return [
            [['person_id'], 'integer'],
            [['status'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = ApplyOrderReturn::find();

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
            'person_id' => $this->person_id,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}