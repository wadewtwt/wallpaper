<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\OperateLog;

class OperateLogSearch extends OperateLog
{
    public $start_time;
    public $end_time;

    public function rules()
    {
        return [
            [['route'], 'string'],
            [['created_at', 'created_by','end_time', 'start_time'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'start_time' => '开始时间',
            'end_time' => '结束时间',
        ]);
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

        $query->andFilterWhere(['between', 'created_at', $this->start_time, $this->end_time]);

        return $dataProvider;
    }
}
