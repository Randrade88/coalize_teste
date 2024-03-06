<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\components\CpfValidator;

class Cliente extends ActiveRecord
{
  public static function tableName()
  {
    return 'cliente';
  }

  public function rules()
  {
    return [
      [["nome", "cpf"], 'unique'],
      [["nome", "cpf"], 'required'],
      [['cpf'], CpfValidator::class],
      [['nome', 'cep', 'logradouro', 'cidade', 'estado', 'complemento'], 'string', 'max' => 255],
      [['telefone'], 'string', 'max' => 20],
      [['foto'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
      [['sexo'], 'in', 'range' => ['M', 'F']],
    ];
  }
  public function getProdutos()
  {
    return $this->hasMany(Produto::class, ['cliente_id' => 'id']);
  }
}
