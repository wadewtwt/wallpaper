<?php

use console\migrations\Migration;

/**
 * Class m180207_124331_drop_fk_alarm_call_alarm_config
 */
class m180207_124331_drop_fk_alarm_call_alarm_config extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-alarm_call-alarm_config', 'alarm_call');
        $this->dropIndex('fk-alarm_call-alarm_config', 'alarm_call');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-alarm_call-alarm_config', 'alarm_call', 'alarm_config_id', 'alarm_config', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180207_124331_drop_fk_alarm_call_alarm_config cannot be reverted.\n";

        return false;
    }
    */
}
