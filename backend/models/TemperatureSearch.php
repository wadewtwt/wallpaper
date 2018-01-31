<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Temperature;

class TemperatureSearch extends Temperature
{
    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['ip', 'port', 'device_no'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Temperature::find();

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
            'store_id' => $this->store_id,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'port', $this->port])
            ->andFilterWhere(['like', 'device_no', $this->device_no]);

        return $dataProvider;
    }
}
