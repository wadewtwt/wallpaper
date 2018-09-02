<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Paper;

class PaperSearch extends Paper
{
    public function rules()
    {
        return [
            [['id', 'type', 'kind', 'praise', 'view', 'created_at','status'], 'integer'],
            [['title', 'introduction'], 'string'],
            [['url'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Paper::find();

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
            'id' => $this->id,
            'type' => $this->type,
            'kind' => $this->kind,
            'praise' => $this->praise,
            'view' => $this->view,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'introduction', $this->introduction]);

        return $dataProvider;
    }
}
