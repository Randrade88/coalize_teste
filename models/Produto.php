<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Produto extends ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%produto}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['preco'], 'number'],
      [['cliente_id'], 'integer'],
      [['nome'], 'string', 'max' => 255],
      [['foto'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'nome' => 'Nome',
      'preco' => 'Preço',
      'cliente_id' => 'Cliente ID',
      'foto' => 'Foto',
    ];
  }
}
