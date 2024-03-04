<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class Cliente extends ActiveRecord
{
    public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  public static function findIdentityByAccessToken($token, $type = null)
  {
    return static::findOne(['access_token' => $token]);
  }

  public function getId()
  {
    return $this->id;
  }

  public function getAuthKey()
  {
    // Return the authentication key if your application uses it
    return $this->auth_key;
  }

  public function validateAuthKey($authKey)
  {
    // Validate the authentication key if your application uses it
    return $this->auth_key === $authKey;
  }

    public function rules()
    {
        return [
            [['cpf'], 'string', 'max' => 14],
            [['nome', 'cep', 'logradouro', 'cidade', 'estado', 'complemento'], 'string', 'max' => 255],
            [['numero'], 'string', 'max' => 20],
            [['foto'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['sexo'], 'in', 'range' => ['M', 'F']],
        ];
    }
}
