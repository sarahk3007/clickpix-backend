<?php

use yii\db\Migration;

/**
 * Class m240126_095407_add_pixel_table
 */
class m240126_095407_add_pixel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'flag' => $this->boolean(),
            'available' => $this->boolean(),
            'paid' => $this->boolean()
        ], 'CHARACTER SET utf8 COLLATE utf8mb3_general_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
    }
}
