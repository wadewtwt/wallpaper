<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\DeviceDetail;

class DeviceDetailSearch extends DeviceDetail
{
    public function rules()
    {
        return [
            [['device_id'], 'integer'],
        ];
    }

    public function search($params)
    {

        $query = DeviceDetail::find();

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
            'device_id' => $this->device_id,
        ]);

        return $dataProvider;
    }
}
