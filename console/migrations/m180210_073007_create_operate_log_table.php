<?php

use console\migrations\Migration;

/**
 * Handles the creation of table `operate_log`.
 */
class m180210_073007_create_operate_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('operate_log', array_merge([
            'id' => $this->primaryKey(),
            'route' => $this->string()->notNull()->comment('路由'),
            'absolute_url' => $this->text()->notNull()->comment('完整 URL'),
            'method' => $this->string()->notNull()->comment('操作类型'),
            'referrer' => $this->string()->comment('Referrer'),
            'user_ip' => $this->string()->comment('访问者ip'),
            'user_agent' => $this->string()->comment('访问者UA'),
            'raw_body' => $this->text()->comment('原始Body'),
            'query_string' => $this->text()->comment('查询参数'),
        ], $this->commonColumns([
            'created_at', 'created_by'
        ])
        ), $this->setTableComment('操作日志'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('operate_log');
    }
}
