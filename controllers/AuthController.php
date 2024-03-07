<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;

class AuthController extends Controller
{
  public function behaviors()
  {
    $behaviors = parent::behaviors();

    // Apply HTTP Bearer authentication only for the 'logout' action
    $behaviors['authenticator'] = [
      'class' => HttpBearerAuth::class,
      'only' => ['logout'],
    ];

    return $behaviors;
  }
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

  public function actionLogout()
  {
    Yii::$app->user->logout(Yii::$app->request->getHeaders()->get('Authorization'));
    Yii::$app->response->format = Response::FORMAT_JSON;
    return ['message' => 'Logout successful'];
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
