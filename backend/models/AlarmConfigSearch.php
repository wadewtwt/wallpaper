<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\AlarmConfig;

class AlarmConfigSearch extends AlarmConfig
{
    public function rules()
    {
        return [
            [['store_id', 'camera_id', 'type'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = AlarmConfig::find();

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
            'camera_id' => $this->camera_id,
            'type' => $this->type,
        ]);

        return $dataProvider;
    }
}
