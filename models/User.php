<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
  public function rules()
  {
    return [
      [['username', 'email', 'password_hash'], 'required'],
      [['email'], 'email'],
      [['email', 'username'], 'unique'],
    ];
  }

  public function beforeSave($insert)
  {
    if (parent::beforeSave($insert)) {
      if ($this->isNewRecord) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
      }
      return true;
    }
    return false;
  }

  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  public static function findIdentityByAccessToken($token, $type = null)
  {
    return static::findOne(['token' => $token]);
  }

  public function getId()
  {
    return $this->id;
  }

  public function getAuthKey()
  {
    return $this->token;
  }

  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }
  
  public static function findByUsername($username)
  {
    return static::findOne(['username' => $username]);
  }
  
  public function validatePassword($password)
  {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }
  public static function validateCredentials($username, $password_hash)
  {return ['wwtoken' => $password_hash];
      $user = static::findOne(['username' => $username]);
      if ($user !== null) {
          return Yii::$app->security->validatePassword($password_hash, $user->password_hash);
      }
      return false;
  }
  public function generateToken($username)
  {
    return Yii::$app->security->generateRandomString();
  }
}
