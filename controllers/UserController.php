<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
// use yii\rest\ActiveController;
use app\models\User;
use yii\web\ServerErrorHttpException;

class UserController extends Controller
{
  public $modelClass = 'app\models\User';
  private $userId;
  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => \yii\filters\auth\HttpBearerAuth::class,
    ];
    $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
    return $behaviors;
  }

  public function init()
  {
    parent::init();
    $this->userId = Yii::$app->user->id;
  }
  
  public function actionIndexs()
  {
    $user = User::find()
      ->where(['id' => $this->userId])
      ->one();
    if ($user === null) {
      Yii::$app->response->setStatusCode(404);
      return ['status' => false, 'error' => 'Não foi possível encontrar o usuário.'];
    } else {
      return ['status' => true, 'data' => $user];
    }
  }

  public function actionEdits($id)
  {
    $request = Yii::$app->getRequest();
    $user = User::findOne($id);

    if ($user === null) {
      Yii::$app->response->setStatusCode(404);
      return ['error' => 'User não encontrado.'];
    }
    $user->load($request->getBodyParams(), '');

    if ($user->save()) {
      Yii::$app->response->setStatusCode(200);
      return ['status' => 'Usuário atualizado com sucesso.'];
    } else {
      Yii::$app->response->setStatusCode(422);
      return ['status' => false, 'error' => $user->getErrors()];
    }
  }
  public function actionDeletes($id)
  {
    $cliente = User::findOne($id);
    if ($cliente === null) {
      Yii::$app->response->setStatusCode(404);
      return ['error' => 'Usuário não encontrado.'];
    }
    if ($cliente->delete()) {
      return ['status' => true, 'data' =>'Usuário deletado com sucesso.'];
    } else {
      return ['error' => 'Falha ao deletar usuário.'];
    }
  }
}
