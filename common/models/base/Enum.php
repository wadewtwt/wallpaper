<?php

namespace common\models\base;

class Enum
{
    const APPLY_ORDER_TYPE_INPUT = 10; // 入库
    const APPLY_ORDER_TYPE_OUTPUT = 20; // 出库
    const APPLY_ORDER_TYPE_APPLY = 30; // 申领
    const APPLY_ORDER_TYPE_RETURN = 40; // 归还

    public static $applyOrderTypeData = [
        self::APPLY_ORDER_TYPE_INPUT => '入库',
        self::APPLY_ORDER_TYPE_OUTPUT => '出库',
        self::APPLY_ORDER_TYPE_APPLY => '申领',
        self::APPLY_ORDER_TYPE_RETURN => '归还'
    ];
}