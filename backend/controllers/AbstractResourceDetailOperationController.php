<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ResourceDetailOperationSearch;
use common\models\Resource;
use Yii;

abstract class AbstractResourceDetailOperationController extends AuthWebController
{
    const RESOURCE_TYPE = Resource::TYPE_DEVICE;

    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResourceDetailOperationSearch([
            'type' => static::RESOURCE_TYPE,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/resource-detail-operation/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
