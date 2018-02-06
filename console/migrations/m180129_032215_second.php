<?php

use console\migrations\Migration;

/**
 * Class m180129_032215_second
 */
class m180129_032215_second extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('temperature', array_merge([
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'name' => $this->string()->notNull()->comment('名称'),
            'ip' => $this->string()->notNull()->comment('IP'),
            'port' => $this->string(10)->notNull()->comment('端口'),
            'device_no' => $this->string()->notNull()->comment('设备号'),
            'down_limit' => $this->decimal(12, 2)->notNull()->comment('下阀值'),
            'up_limit' => $this->decimal(12, 2)->notNull()->comment('上阀值'),
            'current' => $this->decimal(12, 2)->defaultValue(0)->comment('当前值'),
            'current_updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('当前值更新时间'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('温湿度表'));
        $this->addForeignKey('fk-temperature-store', 'temperature', 'store_id', 'store', 'id');

        $this->createTable('camera', array_merge([
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'ip' => $this->string()->notNull()->comment('IP'),
            'port' => $this->string(10)->notNull()->comment('端口'),
            'username' => $this->string()->notNull()->comment('用户名'),
            'password' => $this->string()->notNull()->comment('密码'),
            'name' => $this->string()->notNull()->comment('名称'),
            'device_no' => $this->string()->notNull()->comment('设备号'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('摄像头表'));
        $this->addForeignKey('fk-camera-store', 'camera', 'store_id', 'store', 'id');

        $this->createTable('alarm_config', array_merge([
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'camera_id' => $this->integer()->notNull()->comment('摄像头 ID'),
            'type' => $this->integer()->notNull()->comment('报警类型'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('报警配置表'));
        $this->addForeignKey('fk-alarm_config-store', 'alarm_config', 'store_id', 'store', 'id');
        $this->addForeignKey('fk-alarm_config-camera', 'alarm_config', 'camera_id', 'camera', 'id');

        $this->createTable('alarm_record', array_merge([
            'id' => $this->primaryKey(),
            'alarm_config_id' => $this->integer()->notNull()->comment('报警配置 ID'),
            'alarm_at' => $this->integer()->notNull()->comment('报警时间'),
            'description' => $this->string()->comment('报警描述'),
            'solve_id' => $this->integer()->comment('处理人'),
            'solve_at' => $this->integer()->comment('处理时间'),
            'solve_description' => $this->string()->comment('处理描述'),
            // 冗余字段，alarm_config 中的字段
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'camera_id' => $this->integer()->notNull()->comment('摄像头 ID'),
            'type' => $this->integer()->notNull()->comment('报警类型'),
        ], $this->commonColumns([
            'status', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('报警记录表'));
        $this->addForeignKey('fk-alarm_record-alarm_config', 'alarm_record', 'alarm_config_id', 'alarm_config', 'id');
        $this->addForeignKey('fk-alarm_record-admin', 'alarm_record', 'solve_id', 'admin', 'id');

        $this->createTable('alarm_call', array_merge([
            'id' => $this->primaryKey(),
            'alarm_config_id' => $this->integer()->notNull()->comment('报警配置 ID'),
            'camera_id' => $this->integer()->notNull(),
            // 冗余字段，camera 中的字段
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'ip' => $this->string()->notNull()->comment('IP'),
            'port' => $this->string(10)->notNull()->comment('端口'),
            'username' => $this->string()->notNull()->comment('用户名'),
            'password' => $this->string()->notNull()->comment('密码'),
            'name' => $this->string()->notNull()->comment('名称'),
            'device_no' => $this->string()->notNull()->comment('设备号'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at'
        ])
        ), $this->setTableComment('报警调用表'));
        $this->addForeignKey('fk-alarm_call-alarm_config', 'alarm_call', 'alarm_config_id', 'alarm_config', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180129_032215_second cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180129_032215_second cannot be reverted.\n";

        return false;
    }
    */
}
