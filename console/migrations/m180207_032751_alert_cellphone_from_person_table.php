<?php

use console\migrations\Migration;

/**
 * Class m180207_032751_alert_cellphone_from_person_table
 */
class m180207_032751_alert_cellphone_from_person_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('person', 'cellphone', $this->string()->comment('手机号'));
        $this->alterColumn('admin', 'cellphone', $this->string()->comment('手机号'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('person', 'cellphone', $this->string()->notNull()->comment('手机号'));
        $this->alterColumn('admin', 'cellphone', $this->string()->notNull()->comment('手机号'));
    }

}
