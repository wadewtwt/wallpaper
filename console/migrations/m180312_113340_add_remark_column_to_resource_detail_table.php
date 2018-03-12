<?php

use yii\db\Migration;

/**
 * Handles adding remark to table `resource_detail`.
 */
class m180312_113340_add_remark_column_to_resource_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('resource_detail', 'remark', $this->string()->comment('备注')->after('quantity'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('resource_detail', 'remark');
    }
}
