<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "operate_log".
 *
 * @property integer $id
 * @property string $route
 * @property string $absolute_url
 * @property string $method
 * @property string $referrer
 * @property string $user_ip
 * @property string $user_agent
 * @property string $raw_body
 * @property string $query_string
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property Admin $createdBy
 */
class OperateLog extends \common\models\base\ActiveRecord
{
    // 路由名字定义
    public static $routeNameData = [
        '' => '首页',

        'account/modify-password' => '修改密码',

        'admin' => '管理员管理-列表',
        'admin/index' => '管理员管理-列表',
        'admin/create' => '管理员管理-新增',
        'admin/update' => '管理员管理-更新',
        'admin/reset-password' => '管理员管理-重置密码',
        'admin/change-status' => '管理员管理-禁用启用',

        'alarm-config' => '联动设置管理-列表',
        'alarm-config/index' => '联动设置管理-列表',
        'alarm-config/create' => '联动设置管理-新增',
        'alarm-config/delete' => '联动设置管理-删除',
        'alarm-config/change-status' => '联动设置管理-启用、停用',

        'alarm-record' => '报警记录管理-列表',
        'alarm-record/index' => '报警记录管理-列表',
        'alarm-record/solve' => '报警记录管理-处理',
        'alarm-record/batch-solve' => '报警记录管理-批量处理',
        'alarm-record/generate-apply-order' => '报警记录管理-生成出库或申领单',

        'apply-order-input' => '入库管理-列表',
        'apply-order-input/index' => '入库管理-列表',
        'apply-order-input/create' => '入库管理-新增',
        'apply-order-input/update' => '入库管理-更新',
        'apply-order-input/detail' => '入库管理-明细',
        'apply-order-input/print' => '入库管理-打印',
        'apply-order-input/pass' => '入库管理-审核通过',
        'apply-order-input/delete' => '入库管理-作废',
        'apply-order-input/over' => '入库管理-完成',

        'apply-order-output' => '出库管理-列表',
        'apply-order-output/index' => '出库管理-列表',
        'apply-order-output/create' => '出库管理-新增',
        'apply-order-output/update' => '出库管理-更新',
        'apply-order-output/detail' => '出库管理-明细',
        'apply-order-output/print' => '出库管理-打印',
        'apply-order-output/pass' => '出库管理-审核通过',
        'apply-order-output/delete' => '出库管理-作废',
        'apply-order-output/over' => '出库管理-完成',

        'apply-order-apply' => '申领管理-列表',
        'apply-order-apply/index' => '申领管理-列表',
        'apply-order-apply/create' => '申领管理-新增',
        'apply-order-apply/update' => '申领管理-更新',
        'apply-order-apply/detail' => '申领管理-明细',
        'apply-order-apply/print' => '申领管理-打印',
        'apply-order-apply/pass' => '申领管理-审核通过',
        'apply-order-apply/delete' => '申领管理-作废',
        'apply-order-apply/over' => '申领管理-完成',

        'apply-order-return' => '归还管理-列表',
        'apply-order-return/index' => '归还管理-列表',
        'apply-order-return/detail' => '归还管理-申领明细',
        'apply-order-return/over' => '归还管理-退还',

        'camera' => '摄像头设备管理-列表',
        'camera/index' => '摄像头设备管理-列表',
        'camera/create' => '摄像头设备管理-新增',
        'camera/update' => '摄像头设备管理-更新',
        'camera/delete' => '摄像头设备管理-删除',

        'container' => '货区管理-列表',
        'container/index' => '货区管理-列表',
        'container/create' => '货区管理-新增',
        'container/update' => '货区管理-更新',
        'container/delete' => '货区管理-删除货架',

        'device' => '设备管理-列表',
        'device/index' => '设备管理-列表',
        'device/create' => '设备管理-新增',
        'device/update' => '设备管理-更新',
        'device/delete' => '设备管理-删除',

        'device-detail' => '设备明细管理-列表',
        'device-detail/index' => '设备明细管理-列表',

        'device-detail-operation' => '设备操作记录管理-列表',
        'device-detail-operation/index' => '设备操作记录管理-列表',

        'expendable' => '消耗品管理-列表',
        'expendable/index' => '消耗品管理-列表',
        'expendable/create' => '消耗品管理-新增',
        'expendable/update' => '消耗品管理-更新',
        'expendable/delete' => '消耗品管理-删除',

        'expendable-detail' => '消耗品明细管理-列表',
        'expendable-detail/index' => '消耗品明细管理-列表',

        'expendable-detail-operation' => '消耗品操作记录管理-列表',
        'expendable-detail-operation/index' => '消耗品操作记录管理-列表',

        'home' => '首页',
        'home/index' => '首页',

        'operate-log' => '操作日志管理-列表',
        'operate-log/index' => '操作日志管理-列表',
        'operate-log/more' => '操作日志管理-更多',

        'person' => '人员管理-列表',
        'person/index' => '人员管理-列表',
        'person/create' => '人员管理-新增',
        'person/update' => '人员管理-更新',
        'person/delete' => '人员管理-删除',

        'position' => '职称管理-列表',
        'position/index' => '职称管理-列表',
        'position/create' => '职称管理-新增',
        'position/update' => '职称管理-更新',
        'position/delete' => '职称管理-删除',

        'resource-type' => '资源分类管理-列表',
        'resource-type/index' => '资源分类管理-列表',
        'resource-type/create' => '资源分类管理-新增',
        'resource-type/update' => '资源分类管理-更新',
        'resource-type/delete' => '资源分类管理-删除',

        'store' => '仓库管理-列表',
        'store/index' => '仓库管理-列表',
        'store/create' => '仓库管理-新增',
        'store/update' => '仓库管理-更新',
        'store/delete' => '仓库管理-删除',

        'temperature' => '温湿度设备管理-列表',
        'temperature/index' => '温湿度设备管理-列表',
        'temperature/create' => '温湿度设备管理-新增',
        'temperature/update' => '温湿度设备管理-更新',
        'temperature/delete' => '温湿度设备管理-删除',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operate_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route', 'absolute_url', 'method'], 'required'],
            [['absolute_url', 'raw_body', 'query_string'], 'string'],
            [['created_at', 'created_by'], 'integer'],
            [['route', 'method', 'referrer', 'user_ip', 'user_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => '路由',
            'absolute_url' => '完整 URL',
            'method' => '操作类型',
            'referrer' => 'Referrer',
            'user_ip' => '访问者ip',
            'user_agent' => '访问者UA',
            'raw_body' => '原始Body',
            'query_string' => '查询参数',
            'created_at' => '操作时间',
            'created_by' => '操作人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'created_by']);
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->toName($this->route, static::$routeNameData, $this->route);
    }

    /**
     * 创建一条操作记录
     */
    public static function createOne()
    {
        $request = Yii::$app->request;
        $model = new static([
            'route' => $request->pathInfo,
            'absolute_url' => $request->absoluteUrl,
            'method' => $request->method,
            'referrer' => $request->referrer,
            'user_ip' => $request->userIP,
            'user_agent' => $request->userAgent,
            'raw_body' => urldecode($request->rawBody),
            'query_string' => urldecode($request->queryString),
            'created_at' => time(),
            'created_by' => (Yii::$app->has('user') && !Yii::$app->user->isGuest) ? Yii::$app->user->getId() : 0,
        ]);
        $model->save(false);
    }
}
