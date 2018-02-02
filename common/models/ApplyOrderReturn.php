<?php

namespace common\models;

use common\models\base\Enum;

class ApplyOrderReturn extends ApplyOrder
{
    public static $statusData = [
        self::STATUS_OVER => '借出中',
        self::STATUS_RETURN_OVER => '已归还',
    ];

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->toName($this->status, self::$statusData);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->andWhere(['type' => Enum::APPLY_ORDER_TYPE_APPLY, 'status' => array_keys(static::$statusData)]);
    }
}