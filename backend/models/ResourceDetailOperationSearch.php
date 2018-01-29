<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\ResourceDetailOperation;

class ResourceDetailOperationSearch extends ResourceDetailOperation
{
    public function rules()
    {
        return [
            [['resource_detail_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

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
            'resource_detail_id' => $this->resource_detail_id,
        ]);

        return $dataProvider;
    }
}
