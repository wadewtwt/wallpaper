<?php

use yii\db\Migration;

/**
 * Class m180207_143808_drop_id_from_tag_active_unused
 */
class m180207_143808_drop_id_from_tag_active_unused extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('tag_active_unused', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('tag_active_unused', 'id', $this->primaryKey());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180207_143808_drop_id_from_tag_active_unused cannot be reverted.\n";

        return false;
    }
    */
}
