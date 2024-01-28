<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_token}}`.
 */
class m240126_104037_create_access_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('access_token', [
            'id' => $this->primaryKey(),
            'type' => $this->string(60),
            'issued_date' => $this->string(),
            'issue_ip' => $this->string(255),
            'valid_until' => $this->string(),
            'used' => $this->boolean(),
            'used_date' => $this->string(),
            'used_ip' => $this->string(255),
            'token' => $this->string(400),
            'total_time' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%access_token}}');
    }
}
