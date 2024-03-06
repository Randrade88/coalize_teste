<?php
namespace app\components;
use Yii;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;
use app\models\User;

class TokenAuth extends HttpBearerAuth
{
    public function authenticate($user, $request, $response)
    {
        $token = parent::authenticate($user, $request, $response);
        if ($token !== null) {
            $user = User::find()->where(['token' => $token])->one();

            if ($user !== null) {
                // Authentication successful
                return $user;
            }
        }

        // Authentication failed
        throw new UnauthorizedHttpException('Invalid token');
    }
}
