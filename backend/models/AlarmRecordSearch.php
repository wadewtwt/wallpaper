<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\AlarmRecord;

class AlarmRecordSearch extends AlarmRecord
{
    public function rules()
    {
        return [
            [['alarm_config_id', 'store_id', 'camera_id', 'type'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = AlarmRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'alarm_config_id' => $this->alarm_config_id,
            'store_id' => $this->store_id,
            'camera_id' => $this->camera_id,
            'type' => $this->type,
        ]);

        return $dataProvider;
    }
}
