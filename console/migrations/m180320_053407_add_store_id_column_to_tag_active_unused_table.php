<?php

use console\migrations\Migration;

/**
 * Handles adding store_id to table `tag_active_unused`.
 */
class m180320_053407_add_store_id_column_to_tag_active_unused_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('tag_active_unused', 'store_id', $this->integer()->comment('仓库编号'));
        $this->createIndex('idx-tag_active_unused-store_id-tag_active', 'tag_active_unused', ['store_id', 'tag_active'], true);
        $this->dropIndex('tag_active', 'tag_active_unused');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m180320_053407_add_store_id_column_to_tag_active_unused_table cannot be reverted.\n";

        return false;
    }
}
