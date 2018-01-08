<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\ApplyOrderDetail;

class ApplyOrderDetailSearch extends ApplyOrderDetail
{
    public function rules()
    {
        return [
            [['apply_order_id', 'resource_id', 'container_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = ApplyOrderDetail::find();

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
            'apply_order_id' => $this->apply_order_id,
            'resource_id' => $this->resource_id,
            'container_id' => $this->container_id,
        ]);

        return $dataProvider;
    }
}
