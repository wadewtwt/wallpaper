<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m180312_113856_create_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull()->comment('键'),
            'value' => $this->text()->notNull()->comment('值'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('settings');
    }
}
