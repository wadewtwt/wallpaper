<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Resource;

class ResourceSearch extends Resource
{
    public function rules()
    {
        return [
            [['resource_type_id'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Resource::find()->andWhere(['status' => Resource::STATUS_NORMAL]);

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
            'resource_type_id' => $this->resource_type_id,
            'type' => $this->type,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
