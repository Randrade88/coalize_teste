<?php

namespace app\components;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use app\components\BearerAuth;
use app\models\User;

class BearerAuth extends HttpBearerAuth
{
  public function authenticate($user, $request, $response)
  {
    $accessToken = $request->getHeaders()->get('Authorization');
    if ($accessToken === null || !preg_match('/^Bearer\s+(.*?)$/', $accessToken, $matches)) {
        return null;
    }
    $token = $matches[1];
    // Find the user based on the access token
    $user = User::findIdentityByAccessToken($token);
    if ($user !== null) {
        return $user;
    }
    return null;
  }
  public function actionCreate()
    {
        $user = new User();
        $user->load(Yii::$app->request->post(), '');

        if ($user->save()) {
            return ['message' => 'User created successfully.', 'user' => $user];
        } else {
            return ['errors' => $user->errors];
        }
    }
}
