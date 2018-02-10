<?php

namespace backend\components;

use common\models\OperateLog;
use Yii;
use yii\base\ActionFilter;

class OperateLogFilter extends ActionFilter
{
    public $exceptRoutes = [];

    public function beforeAction($action)
    {
        $currentRoute = Yii::$app->request->pathInfo;
        $exceptMatch = false;
        foreach ($this->exceptRoutes as $exceptRoute) {
            if (fnmatch($exceptRoute, $currentRoute)) {
                $exceptMatch = true;
                break;
            }
        }
        if (!$exceptMatch) {
            OperateLog::createOne();
        }
        return parent::beforeAction($action);
    }
}