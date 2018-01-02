<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\ExpendableDetail;

class ExpendableDetailSearch extends ExpendableDetail
{
    public function rules()
    {
        return [
            [['operation'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = ExpendableDetail::find();

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
            'operation' => $this->operation,
            'resource_id' => $this->resource_id
        ]);

        return $dataProvider;
    }
}
