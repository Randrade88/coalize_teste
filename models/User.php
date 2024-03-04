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

    // Hash password before saving to database
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

    // IdentityInterface methods (required for Bearer Authentication)
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement if needed
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }

    // Find user by username
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    // Validate password
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function generateToken($username)
    {
        return Yii::$app->security->generateRandomString();
    }
}
