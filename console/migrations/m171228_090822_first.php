<?php

use console\migrations\Migration;

/**
 * Class m171228_090822_first
 */
class m171228_090822_first extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('position', array_merge([
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('名称'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('职位表'));

        $this->createTable('person', array_merge([
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('姓名'),
            'cellphone' => $this->string()->notNull()->comment('手机号'),
            'position_id' => $this->integer()->notNull()->comment('职位'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('人员表'));
        $this->addForeignKey('fx-person-position', 'person', 'position_id', 'position', 'id');

        $this->createTable('container', array_merge([
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
            'total_quantity' => $this->integer()->notNull()->comment('货位数量'),
            'free_quantity' => $this->integer()->notNull()->comment('空闲数量'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('货位表'));

        $this->createTable('resource', array_merge([
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull()->comment('类型:消耗品、设备'),
            'name' => $this->string()->notNull()->comment('名称'),
            'min_stock' => $this->integer()->notNull()->defaultValue(0)->comment('最低库存'),
            'current_stock' => $this->integer()->notNull()->defaultValue(0)->comment('当前库存'),
            'scrap_cycle' => $this->integer()->notNull()->defaultValue(0)->comment('报废周期（天）'),
            'maintenance_cycle' => $this->integer()->notNull()->defaultValue(0)->comment('维护周期（天）'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('设备资源表'));

        $this->createTable('expendable_detail', array_merge([
            'id' => $this->primaryKey(),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'container_id' => $this->integer()->notNull()->comment('货位 ID'),
            'rfid' => $this->string()->notNull()->comment('RFID'),
            'operation' => $this->integer()->notNull()->comment('操作:出库、入库'),
            'quantity' => $this->integer()->notNull()->comment('数量'),
            'remark' => $this->string()->notNull()->comment('说明'),
            'scrap_at' => $this->integer()->notNull()->comment('报废时间'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by',
        ])
        ), $this->setTableComment('消耗品明细表'));
        $this->addForeignKey('fx-expendable_detail-resource', 'expendable_detail', 'resource_id', 'resource', 'id');
        $this->addForeignKey('fx-expendable_detail-container', 'expendable_detail', 'container_id', 'container', 'id');

        $this->createTable('device', array_merge([
            'id' => $this->primaryKey(),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'container_id' => $this->integer()->notNull()->comment('货位 ID'),
            'rfid' => $this->string()->notNull()->comment('RFID'),
            'is_online' => $this->boolean()->notNull()->defaultValue(false)->comment('是否在线'),
            'online_change_at' => $this->integer()->notNull()->comment('在线离线时间'),
            'maintenance_at' => $this->integer()->notNull()->comment('最近维护时间'),
            'scrap_at' => $this->integer()->notNull()->comment('报废时间'),
            'quantity' => $this->integer()->notNull()->comment('数量'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('设备明细表'));
        $this->addForeignKey('fx-device-resource', 'device', 'resource_id', 'resource', 'id');
        $this->addForeignKey('fx-device-container', 'device', 'container_id', 'container', 'id');

        $this->createTable('device_detail', array_merge([
            'id' => $this->primaryKey(),
            'device_id' => $this->integer()->notNull()->comment('设备 ID'),
            'operation' => $this->smallInteger(1)->notNull()->comment('操作'),
            'remark' => $this->string()->notNull()->comment('说明'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('设备使用明细表'));
        $this->addForeignKey('fx-device_detail-device', 'device_detail', 'device_id', 'device', 'id');

        $this->createTable('apply_order', array_merge([
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull()->comment('类别:入库、出库、申领、归还'),
            'person_id' => $this->integer()->notNull()->comment('申请人 ID'),
            'reason' => $this->string()->notNull()->comment('理由'),
            'delete_reason' => $this->string()->comment('作废理由'),
            'pick_type' => $this->integer()->notNull()->defaultValue(0)->comment('申领类型:使用、保养、拆封'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('申请单表'));
        $this->addForeignKey('fx-apply_order-person', 'apply_order', 'person_id', 'person', 'id');

        $this->createTable('apply_order_detail', array_merge([
            'id' => $this->primaryKey(),
            'apply_order_id' => $this->integer()->notNull()->comment('入库单 ID'),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'container_id' => $this->integer()->notNull()->comment('货位 ID'),
            'rfid' => $this->string()->comment('RFID'),
            'quantity' => $this->integer()->notNull()->defaultValue(0)->comment('数量'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('申请单详情表'));
        $this->addForeignKey('fx-apply_order_detail-apply_order', 'apply_order_detail', 'apply_order_id', 'apply_order', 'id');
        $this->addForeignKey('fx-apply_order_detail-resource', 'apply_order_detail', 'resource_id', 'resource', 'id');
        $this->addForeignKey('fx-apply_order_detail-container', 'apply_order_detail', 'container_id', 'container', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171228_090822_first cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171228_090822_first cannot be reverted.\n";

        return false;
    }
    */
}
