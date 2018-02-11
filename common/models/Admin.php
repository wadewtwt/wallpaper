<?php

namespace common\models;

use common\components\Tools;
use common\models\base\ActiveRecord;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $name
 * @property string $cellphone
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $auth_role
 * @property integer $admin_role
 *
 * @property AlarmRecord[] $alarmRecords
 * @property string $statusName
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const SUPER_ADMIN_ID = 1;

    const STATUS_NORMAL = 0; // 正常
    const STATUS_DISABLE = 10; // 不可登录

    const ADMIN_ROLE_SUPER_ADMIN = 0; // 超级管理员
    const ADMIN_ROLE_NORMAL_ADMIN = 1; // 普通管理员

    public static $statusData = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_DISABLE => '不可用',
    ];

    public static $adminRoleData = [
        self::ADMIN_ROLE_SUPER_ADMIN => '超级管理员',
        self::ADMIN_ROLE_NORMAL_ADMIN => '普通管理员',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'name', 'auth_key'], 'required'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'admin_role'], 'integer'],
            [['username', 'password_hash', 'name', 'cellphone', 'auth_key', 'auth_role'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '登录名',
            'password_hash' => '密码',
            'name' => '管理员姓名',
            'cellphone' => '手机号',
            'auth_key' => 'Auth Key',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
            'auth_role' => 'Auth Role',
            'admin_role' => '管理员角色',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmRecords()
    {
        return $this->hasMany(AlarmRecord::className(), ['solve_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->toName($this->status, self::$statusData);
    }

    /**
     * @return string
     */
    public function getAdminRoleName()
    {
        return $this->toName($this->admin_role, self::$adminRoleData);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->primaryKey;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 生成auth_key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Tools::generateRandString();
    }

    /**
     * 设置密码
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Tools::generatePasswordHash($password);
    }

    /**
     * 校验密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Tools::validatePassword($password, $this->password_hash);
    }

    /**
     * @param bool $map
     * @return array|Store[]
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