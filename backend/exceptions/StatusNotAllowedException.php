<?php

namespace backend\exceptions;

use yii\web\HttpException;

class StatusNotAllowedException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = '该数据状态不允许执行该操作', $code = 0, \Exception $previous = null)
    {
        parent::__construct(403, $message, $code, $previous);
    }
}