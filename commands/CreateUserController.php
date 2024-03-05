<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class CreateUserController extends Controller
{
  public function actionIndex($username, $password_hash, $email)
  {
    $user = new User();
    $user->username = $username;
    $user->password_hash = Yii::$app->security->generatePasswordHash($password_hash);
    $user->email = $email;
    if ($user->save()) {
        echo "Usuário cadastrado com sucesso!\n";
    } else {
        echo "Error ao inserir usuário:\n";
        print_r($user->errors);
    }
  }
}
