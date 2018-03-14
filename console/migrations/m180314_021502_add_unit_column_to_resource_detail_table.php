<?php

use yii\db\Migration;

/**
 * Handles adding unit to table `resource_detail`.
 */
class m180314_021502_add_unit_column_to_resource_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('resource_detail', 'tag_active', $this->string()->comment('有源标签'));
        $this->alterColumn('resource_detail', 'tag_passive', $this->string()->comment('无源标签'));

        $this->addColumn('resource', 'unit', $this->smallInteger()->null()->defaultValue(0)->comment('计量单位')->after('maintenance_cycle'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('resource_detail', 'tag_active', $this->string()->notNull()->comment('有源标签'));
        $this->alterColumn('resource_detail', 'tag_passive', $this->string()->notNull()->comment('无源标签'));

        $this->dropColumn('resource', 'unit');
    }
}
