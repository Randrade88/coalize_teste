<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cliente}}`.
 */
class m240303_221523_create_cliente_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%cliente}}', [
      'id' => $this->primaryKey(),
      'nome' => $this->string(),
      'cpf' => $this->string(14),
      'cep' => $this->string(),
      'logradouro' => $this->string(),
      'telefone' => $this->string(),
      'cidade' => $this->string(),
      'estado' => $this->string(),
      'complemento' => $this->string(),
      'foto' => $this->string(),
      'sexo' => $this->string(2),
      'user_id' => $this->integer(),
      'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
    ]);
    $this->addForeignKey(
      'fk-cliente-user_id',
      'cliente',
      'user_id',
      'user',
      'id',
      'CASCADE'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropForeignKey('fk-cliente-user_id', 'cliente');
    $this->dropTable('{{%cliente}}');
  }
}
