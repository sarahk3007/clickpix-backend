<?php

use yii\db\Migration;

/**
 * Class m240129_131230_add_user_table
 */
class m240129_131230_add_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'activation_key' => $this->string(300),
        ], 'CHARACTER SET utf8 COLLATE utf8mb3_general_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
