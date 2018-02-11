<?php

use yii\db\Migration;

/**
 * Handles adding admin_role to table `admin`.
 */
class m180211_021302_add_admin_role_column_to_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('admin', 'admin_role', $this->integer()->notNull()->defaultValue(0)->comment('管理员角色'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('admin', 'admin_role');
    }
}
