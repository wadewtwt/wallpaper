<?php

use yii\db\Migration;

/**
 * Handles adding apply_order_id to table `resource_detail_operation`.
 */
class m180207_033542_add_apply_order_id_column_to_resource_detail_operation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('resource_detail_operation', 'apply_order_id', $this->integer()->notNull()->comment('申请单 ID')->after('resource_detail_id'));
        // 由于有以前的数据存在，所以此处先不做外键关联
        //$this->addForeignKey('fk-resource_detail_operation-apply_order', 'resource_detail_operation', 'apply_order_id', 'apply_order', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('resource_detail_operation', 'apply_order_id');
    }
}
