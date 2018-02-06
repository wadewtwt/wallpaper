<?php

use console\migrations\Migration;

/**
 * Handles the creation of table `resource_type`.
 */
class m180206_140836_create_resource_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('resource_type', array_merge([
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
        ], $this->commonColumns([
            'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
        ])
        ), $this->setTableComment('资源分类表'));

        $this->addColumn('resource', 'resource_type_id', $this->integer()->notNull()->comment('分类')->after('type'));
        // 由于有以前的数据存在，所以此处先不做外键关联
        //$this->addForeignKey('fk-resource-resource_type', 'resource', 'resource_type_id', 'resource_type', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        //$this->dropForeignKey('fk-resource-resource_type', 'resource');
        $this->dropColumn('resource', 'resource_type_id');
        $this->dropTable('resource_type');
    }
}
