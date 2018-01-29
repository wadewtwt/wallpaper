<?php

namespace backend\controllers;

use backend\models\ResourceDetailOperationSearch;
use Yii;

class ResourceDetailOperationController extends AbstractResourceController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResourceDetailOperationSearch([
            'type' => $this->resourceType,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/resource-detail-operation/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
