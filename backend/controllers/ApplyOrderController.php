<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ApplyOrderSearch;
use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use common\models\Resource;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ApplyOrderController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new ApplyOrder();
        $models = [new ApplyOrderDetail()];

        $request = Yii::$app->getRequest();

        if ($request->isPost) {
            $data = $request->post('ApplyOrderDetail', []);
            foreach (array_keys($data) as $index) {
                $models[$index] = new ApplyOrderDetail();
            }
            Model::loadMultiple($models, $request->post());
            if ($request->post('ajax') !== null) {
                // models 数据的 ajax 验证
                Yii::$app->response->format = Response::FORMAT_JSON;
                $result = ActiveForm::validateMultiple($models);
                return $result;
            }
            // 装载数据，处理业务
            if ($model->load($request->post())) {
                $model->type = ApplyOrder::OPERATION_INPUT;
                $model->save(false);
                foreach ($models as $detail) {
                    /** @var $detail ApplyOrderDetail */
                    $detail->apply_order_id = $model->id;
                    $detail->save(false);
                }
                return $this->actionPreviousRedirect();
            }
        }

        $resourceData = $this->getResourceData(ArrayHelper::getColumn($models, 'resource_id'));

        return $this->render('create_update', [
            'model' => $model,
            'models' => $models,
            'resourceData' => $resourceData
        ]);
    }

    // 打印
    public function actionPrint($id)
    {
        // TODO
    }

    // 修改
    public function actionUpdate($id)
    {
        // TODO
    }

    // 作废
    public function actionDelete($id)
    {
        // TODO
    }

    // 明细
    public function actionDetail($id)
    {
        // TODO
    }

    // 审核通过
    public function actionPass($id)
    {
        // TODO
    }

    /**
     * 获取资源的 id 和 name 信息
     * @param $resourceIds
     * @return array
     */
    protected function getResourceData($resourceIds)
    {
        return ArrayHelper::map(ArrayHelper::toArray(Resource::find()->select(['id', 'name', 'type'])->where(['id' => $resourceIds])->all(), [
            Resource::className() => [
                'id',
                'nameType' => function (\common\models\Resource $model) {
                    return $model->name . '(' . $model->getTypeName() . ')';
                }
            ]
        ]), 'id', 'nameType');
    }

}
