<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "person".
 *
 * @property integer $id
 * @property string $name
 * @property string $cellphone
 * @property integer $position_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ApplyOrder[] $applyOrders
 * @property Position $position
 */
class Person extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0;
    const STATUS_DELETE = 10;

    public static $statusData = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_DELETE => '已删',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'position_id'], 'required'],
            [['position_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'cellphone'], 'string', 'max' => 255],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'cellphone' => '手机号',
            'position_id' => '职位',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrders()
    {
        return $this->hasMany(ApplyOrder::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @param bool $map
     * @return array|\yii\db\ActiveRecord[]|Person
     */
    public static function findAllIdName($map = false)
    {
        $model = self::find()->select(['id', 'name'])->where(['status' => self::STATUS_NORMAL])->asArray()->all();
        if ($map) {
            return ArrayHelper::map($model, 'id', 'name');
        }
        return $model;
    }

}
