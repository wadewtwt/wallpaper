<?php

use yii\db\Migration;

/**
 * Handles adding store_ids to table `admin`.
 */
class m180313_052800_add_store_ids_column_to_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('admin', 'store_ids', $this->string()->comment('管理仓库'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('admin', 'store_ids');
    }
}
