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
            'created_by' => Yii::$app->user->getId(),
        ]);
        $model->save(false);
    }
}
