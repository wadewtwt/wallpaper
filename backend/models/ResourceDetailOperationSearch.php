<?php

namespace backend\models;

use common\components\ActiveDataProvider;
use common\models\Resource;
use common\models\ResourceDetailOperation;

class ResourceDetailOperationSearch extends ResourceDetailOperation
{
    public $created_at_1;
    public $created_at_2;

    public $person_id;

    public function rules()
    {
        return [
            [['resource_detail_id'], 'integer'],
            [['created_at_1', 'created_at_2'], 'safe'],
            ['person_id', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'person_id' => '操作人',
        ]);
    }

    public function search($params)
    {
        $query = static::find()->alias('a')->joinWith(['resource b', 'applyOrder c'])
            ->andWhere(['b.status' => Resource::STATUS_NORMAL]);

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
            'a.type' => $this->type,
            'a.resource_detail_id' => $this->resource_detail_id,
            'c.person_id' => $this->person_id,
        ]);

        if ($this->created_at_1 && $this->created_at_2) {
            $query->andWhere(['between', 'a.created_at', $this->created_at_1, $this->created_at_2]);
        }

        return $dataProvider;
    }
}
