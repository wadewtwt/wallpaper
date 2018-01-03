<?php

namespace common\models;

/**
 * This is the model class for table "apply_order".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $person_id
 * @property string $reason
 * @property string $delete_reason
 * @property integer $pick_type
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Person $person
 * @property ApplyOrderDetail[] $applyOrderDetails
 */
class ApplyOrder extends \common\models\base\ActiveRecord
{
    const OPERATION_INPUT = 10;
    const OPERATION_OUTPUT = 20;
    const OPERATION_APPLY = 30;
    const OPERATION_RETURN = 40;
    public static $operationData = [
        self::OPERATION_INPUT => '入库',
        self::OPERATION_OUTPUT => '出库',
        self::OPERATION_APPLY => '申领',
        self::OPERATION_RETURN => '归还'
    ];

    const PICK_TYPE_USE = 10;
    const PICK_TYPE_MAINTENANCE = 20;
    const PICK_TYPE_SEAL_OFF = 30;
    public static $pickTypeData = [
        self::PICK_TYPE_USE => '使用',
        self::PICK_TYPE_MAINTENANCE => '保养',
        self::PICK_TYPE_SEAL_OFF => '拆封'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'person_id', 'reason'], 'required'],
            [['type', 'person_id', 'pick_type', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['reason', 'delete_reason'], 'string', 'max' => 255],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类别:入库、出库、申领、归还',
            'person_id' => '申请人 ID',
            'reason' => '理由',
            'delete_reason' => '作废理由',
            'pick_type' => '申领类型:使用、保养、拆封',
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
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderDetails()
    {
        return $this->hasMany(ApplyOrderDetail::className(), ['apply_order_id' => 'id']);
    }

    /**
     * 类别操作：入库、出库、申领、归还
     * @return string
     */
    public function operationData(){
        return $this->toName($this->type,self::$operationData);
    }

    /**
     * 申领类型：使用、保养、拆封
     * @return string
     */
    public function pickTypeData(){
        return $this->toName($this->pick_type,self::$pickTypeData);
    }
}
