<?php

use yii\db\Migration;

/**
 * Class m180319_063539_alert_alarm_config_id_column_to_alarm_record_table
 */
class m180319_063539_add_remark_column_to_alarm_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('alarm_config', 'remark', $this->string()->comment('备注'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('alarm_config', 'remark');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180319_063539_alert_alarm_config_id_column_to_alarm_record_table cannot be reverted.\n";

        return false;
    }
    */
}
