<?php

use yii\db\Migration;

/**
 * Handles adding flag to table `alarm_record`.
 */
class m180209_184341_add_flag_column_to_alarm_record_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('alarm_record', 'flag', $this->string()->comment('标记')->after('alarm_config_id'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('alarm_record', 'flag');
    }
}
