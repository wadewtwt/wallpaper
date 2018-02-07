<?php

namespace backend\models\form;

use common\models\AlarmRecord;
use yii\base\Model;

class AlarmRecordBatchSolveForm extends Model
{
    /**
     * AlarmRecord 的 id
     * 逗号隔开
     * @var string
     */
    public $keys;
    /**
     * @var integer
     */
    public $solve_id;
    /**
     * @var string
     */
    public $solve_description;

    public function rules()
    {
        return [
            [['solve_id'], 'required'],
            ['solve_id', 'integer'],
            [['keys', 'solve_description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'solve_id' => '处理人',
            'solve_description' => '处理描述',
        ];
    }

    /**
     * @param $operateUserId
     * @return array
     */
    public function solve($operateUserId)
    {
        $keys = array_filter(explode(',', $this->keys));

        $count = AlarmRecord::updateAll([
            'solve_id' => $this->solve_id,
            'solve_description' => $this->solve_description ?: AlarmRecord::SOLVE_DESCRIPTION_DEFAULT,
            'solve_at' => time(),
            'status' => AlarmRecord::STATUS_SOLVED,
            'updated_at' => time(),
            'updated_by' => $operateUserId,
        ], [
            'id' => $keys,
            'status' => AlarmRecord::STATUS_NORMAL
        ]);
        return ['type' => 'success', 'msg' => "操作成功{$count}条记录"];
    }
}