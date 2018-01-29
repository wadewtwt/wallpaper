<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\models\Resource;
use yii\base\Exception;

abstract class AbstractResourceController extends AuthWebController
{
    public $resourceType;

    public function init()
    {
        parent::init();
        $allows = array_keys(Resource::$typeData);
        if (!in_array($this->resourceType, $allows)) {
            throw new Exception('resourceType 必须配置，且为 ' . implode(',', $allows) . '中的一个');
        }
    }
}
