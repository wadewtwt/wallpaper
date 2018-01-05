<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "position".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Person[] $people
 */
class Position extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'position';
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
            [['name'], 'unique'],
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
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['position_id' => 'id']);
    }

    /**
     * @param bool $map
     * @return array|Position[]
     */
    public static function findAllIdName($map = false)
    {
        $models = static::find()->select(['id', 'name'])->asArray()->all();
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }
}
