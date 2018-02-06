<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "resource_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Resource[] $resource
 */
class ResourceType extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
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
    public function getResources()
    {
        return $this->hasMany(Resource::className(), ['resource_type_id' => 'id']);
    }

    /**
     * @param bool $map
     * @param bool $withQuantity
     * @return array|Container[]
     */
    public static function findAllIdName($map = false)
    {
        $models = self::find()->select(['id', 'name'])->asArray()->all();
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }
}