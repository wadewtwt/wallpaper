<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\OperateLog;

class OperateLogSearch extends OperateLog
{
    public function rules()
    {
        return [
            [['route'], 'string'],
            [['created_at', 'created_by'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = OperateLog::find()->with('createdBy');

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
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'route', $this->route]);

        return $dataProvider;
    }
}
