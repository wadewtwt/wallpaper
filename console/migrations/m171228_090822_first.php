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
        $this->addForeignKey('fk-person-position', 'person', 'position_id', 'position', 'id');

        $this->createTable('store', array_merge([
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('仓库表'));

        $this->createTable('container', array_merge([
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull()->comment('仓库 ID'),
            'name' => $this->string()->notNull()->comment('名称'),
            'total_quantity' => $this->integer()->notNull()->comment('货位数量'),
            'current_quantity' => $this->integer()->notNull()->defaultValue(0)->comment('当前数量'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('货位表'));
        $this->addForeignKey('fk-container-store', 'container', 'store_id', 'store', 'id');

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

        $this->createTable('resource_detail', array_merge([
            'id' => $this->primaryKey(),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'type' => $this->integer()->notNull()->comment('类型:消耗品、设备'),
            'container_id' => $this->integer()->notNull()->comment('货位 ID'),
            'tag_active' => $this->string()->notNull()->comment('有源标签'),
            'tag_passive' => $this->string()->notNull()->comment('无源标签'),
            'is_online' => $this->boolean()->notNull()->defaultValue(false)->comment('是否在线'),
            'online_change_at' => $this->integer()->notNull()->comment('在线离线时间'),
            'maintenance_at' => $this->integer()->notNull()->comment('最近维护时间'),
            'scrap_at' => $this->integer()->notNull()->comment('报废时间'),
            'quantity' => $this->integer()->notNull()->comment('数量'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('资源明细表'));
        $this->addForeignKey('fk-resource_detail-resource', 'resource_detail', 'resource_id', 'resource', 'id');
        $this->addForeignKey('fk-resource_detail-container', 'resource_detail', 'container_id', 'container', 'id');

        $this->createTable('resource_detail_operation', array_merge([
            'id' => $this->primaryKey(),
            'resource_detail_id' => $this->integer()->notNull()->comment('设备 ID'),
            'type' => $this->integer()->notNull()->comment('类型:消耗品、设备'),
            'operation' => $this->smallInteger(1)->notNull()->comment('操作'),
            'remark' => $this->string()->comment('备注'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by'
        ])
        ), $this->setTableComment('资源使用明细表'));
        $this->addForeignKey('fk-resource_detail_operation-resource_detail', 'resource_detail_operation', 'resource_detail_id', 'resource_detail', 'id');

        $this->createTable('tag_active_unused', [
            'id' => $this->primaryKey(),
            'tag_active' => $this->string()->notNull()->unique()->comment('有源标签'),
        ], $this->setTableComment('有源标签未使用表'));

        $this->createTable('apply_order', array_merge([
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull()->comment('类别:入库、出库、申领、归还'),
            'person_id' => $this->integer()->notNull()->comment('申请人 ID'),
            'reason' => $this->string()->notNull()->comment('理由'),
            'delete_reason' => $this->string()->comment('作废理由'),
            'pick_type' => $this->integer()->notNull()->defaultValue(0)->comment('申领类型:使用、保养、拆封'),
            'return_at' => $this->integer()->notNull()->defaultValue(0)->comment('归还时间'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('申请单表'));
        $this->addForeignKey('fk-apply_order-person', 'apply_order', 'person_id', 'person', 'id');

        $this->createTable('apply_order_detail', [
            'id' => $this->primaryKey(),
            'apply_order_id' => $this->integer()->notNull()->comment('申请单 ID'),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'quantity' => $this->integer()->notNull()->defaultValue(0)->comment('数量'),
            'quantity_real' => $this->integer()->comment('实际数量'),
            'quantity_return' => $this->integer()->comment('归还数量'),
        ], $this->setTableComment('申请单明细表'));
        $this->addForeignKey('fk-apply_order_detail-apply_order', 'apply_order_detail', 'apply_order_id', 'apply_order', 'id');
        $this->addForeignKey('fk-apply_order_detail-resource', 'apply_order_detail', 'resource_id', 'resource', 'id');

        $this->createTable('apply_order_resource', [
            'id' => $this->primaryKey(),
            'is_return' => $this->boolean()->notNull()->defaultValue(false)->comment('是否是归还'),
            'apply_order_id' => $this->integer()->notNull()->comment('申请单 ID'),
            'resource_id' => $this->integer()->notNull()->comment('资源 ID'),
            'container_id' => $this->integer()->notNull()->comment('货位 ID'),
            'tag_active' => $this->string()->comment('有源标签'),
            'tag_passive' => $this->string()->comment('无源标签'),
            'quantity' => $this->integer()->notNull()->defaultValue(0)->comment('数量'),
            'remark' => $this->string()->comment('备注'),
        ], $this->setTableComment('申请单资源明细表'));
        $this->addForeignKey('fk-apply_order_resource-apply_order', 'apply_order_resource', 'apply_order_id', 'apply_order', 'id');
        $this->addForeignKey('fk-apply_order_resource-resource', 'apply_order_resource', 'resource_id', 'resource', 'id');
        $this->addForeignKey('fk-apply_order_resource-container', 'apply_order_resource', 'container_id', 'container', 'id');

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
