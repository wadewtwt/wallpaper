<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\ApplyOrder;

class ApplyOrderSearch extends ApplyOrder
{
    public function rules()
    {
        return [
            [['type', 'person_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = ApplyOrder::find()->andWhere(['type' => ApplyOrder::OPERATION_INPUT]);

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
        ]);

        return $dataProvider;
    }
}
