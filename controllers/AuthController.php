<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;

class AuthController extends Controller
{
  public function actionLogin()
  {
    $username = Yii::$app->request->post('username');
    $password_hash = Yii::$app->request->post('password_hash');
    
    if (User::validateCredentials($username, $password_hash)) {

      $user = User::findByUsername($username);

      $token = User::GenerateToken($user);
      $user->token = $token;
      $user->save();
      return ['token' => $user->token];
    } else {
      // Return error response
      Yii::$app->response->statusCode = 401;
      return ['error' => 'Unauthorized'];
    }
  }
  private function validateCredentials($username, $password_hash)
  {
    // Retrieve user record from database by username
    $user = User::findByUsername($username);

    // If user found, verify password
    if ($user !== null) {
      // Verify password hash
      return Yii::$app->security->validatePassword($password_hash, $user->password_hash);
    }

    return false; // User not found or password incorrect
  }

  // Other helper methods...
}
