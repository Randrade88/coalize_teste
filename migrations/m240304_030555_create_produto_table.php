<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%produto}}`.
 */
class m240304_030555_create_produto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%produto}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'preco' => $this->decimal(10, 2)->notNull(),
            'cliente_id' => $this->integer()->notNull(),
            'foto' => $this->string(),
        ]);
        $this->addForeignKey(
            'fk-produto-cliente_id',
            'produto',
            'cliente_id',
            'cliente',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {   
        $this->dropForeignKey('fk-produto-cliente_id', 'produto');
        $this->dropTable('{{%produto}}');
    }
}
