<?php

use yii\db\Migration;

/**
 * Handles adding resource_tag to table `alarm_record`.
 */
class m180210_052332_add_resource_tag_column_to_alarm_record_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('alarm_record', 'resource_tag', $this->string()->comment('资源标签'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('alarm_record', 'resource_tag');
    }
}
