<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240303_030843_create_user_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%user}}', [
      'id' => $this->primaryKey(),
      'username' => $this->string()->notNull(),
      'email' => $this->string()->notNull()->unique(),
      'password_hash' => $this->string()->notNull(),
      'auth_key' => $this->string(),
      'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropTable('{{%user}}');
  }
}
