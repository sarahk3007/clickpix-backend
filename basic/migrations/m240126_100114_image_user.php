<?php

use yii\db\Migration;

/**
 * Class m240126_100114_image_user
 */
class m240126_100114_image_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image_user', [
            'id' => $this->primaryKey(),
            'image_id' => $this->integer(),
            //'phone' => $this->integer(10),
            'flag' => $this->boolean(),
            'email' => $this->string(100),
            'name' => $this->string(500),
            'created' => $this->bigInteger()
        ], 'CHARACTER SET utf8 COLLATE utf8mb3_general_ci');
        $this->addForeignKey('fk_image_user', 'image_user', 'image_id', 'image', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image_user');
    }
}
