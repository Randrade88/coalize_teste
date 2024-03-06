<?php

use yii\db\Migration;

/**
 * Class m240306_051524_add_user_id_to_produto_table
 */
class m240306_051524_add_user_id_to_produto_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('produto', 'user_id', $this->integer()->notNull());
    $this->addForeignKey(
      'fk-produto-user_id',
      'produto',
      'user_id',
      'user',
      'id',
      'CASCADE',
      'CASCADE'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropForeignKey('fk-produto-user_id', 'produto');
    $this->dropColumn('produto', 'user_id');
    return false;
  }

  /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240306_051524_add_user_id_to_produto_table cannot be reverted.\n";

        return false;
    }
    */
}
