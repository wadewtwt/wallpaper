<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Device;

class DeviceSearch extends Device
{
    public function rules()
    {
        return [
            [['resource_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Device::find();

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
            'resource_id' => $this->resource_id,
        ]);

        return $dataProvider;
    }
}
