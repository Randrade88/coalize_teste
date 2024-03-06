<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\Cliente;
use app\components\TokenAuth;
use yii\web\NotFoundHttpException;
use app\models\Produto;

class ClienteController extends ActiveController
{
  public $modelClass = 'app\models\Cliente';
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

  public function actionIndexs($id)
  {
    $cliente = Cliente::find()
      ->where(['user_id' => $this->userId])
      ->andWhere(['id' => $id])
      ->one();

    if ($cliente === null) {
      Yii::$app->response->setStatusCode(404);
      return ['status' => false, 'error' => 'Não foi possível encontrar o Cliente.'];
    } else {
      return ['status' => true, 'data' => $cliente];
    }
  }
  public function actionEdits($id)
  {
    $request = Yii::$app->getRequest();
    $cliente = Cliente::find()
      ->where(['user_id' => $this->userId])
      ->andWhere(['id' => $id])
      ->one();

    if ($cliente === null) {
      Yii::$app->response->setStatusCode(404);
      return ['error' => 'Cliente não encontrado.'];
    }
    $cliente->load($request->getBodyParams(), '');

    if ($cliente->save()) {
      Yii::$app->response->setStatusCode(200);
      return ['status' => 'Client atualizado com sucesso.'];
    } else {
      Yii::$app->response->setStatusCode(422);
      return ['status' => false ,'error' => $cliente->getErrors()];
    }
  }

  public function actionList()
  {
    $page = Yii::$app->request->get('page', 1);
    $query = Cliente::find()->where(['user_id' => $this->userId]);
    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 10,
      ],
    ]);
    $dataProvider->pagination->setPage($page - 1);
    $clients = $dataProvider->getModels();
    Yii::$app->response->format = Response::FORMAT_JSON;
    return [
      'success' => true,
      'data' => $clients,
      'pagination' => [
        'total_count' => $dataProvider->getTotalCount(),
        'page' => $page,
        'page_count' => $dataProvider->pagination->getPageCount(),
        'page_size' => $dataProvider->pagination->getPageSize(),
      ],
    ];
  }

  public function actionCreates()
  {
    $model = new $this->modelClass;
    $model->load(Yii::$app->request->post(), '');
    $model->user_id = $this->userId;;
    if ($model->save()) {
      return ['status' => true, 'data' => "Cliente cadastrado com sucesso"];
    } else {
      return ['status' => false, 'data' => $model->errors];
    }
  }
  public function actionSearchByCliente($cliente_id)
  {
    $clientes = Cliente::find()->where(['id' => $cliente_id])->all();
    if (!$clientes) {
      return ['status' => false, 'data' => "Cliente não encontrado"];
    }
    return $clientes;
  }
  public function actionProdutosByCliente($cliente_id)
  {
    $cliente = Cliente::findOne($cliente_id);
    if (!$cliente) {
      return ['error' => 'Cliente não encontrado'];
    }
    $produtos = Produto::find()->where(['cliente_id' => $cliente_id])->all();
    Yii::$app->response->format = Response::FORMAT_JSON;
    return $produtos;
  }
  public function actionDeletes($id)
  {
    $cliente = Cliente::findOne($id);
    if ($cliente === null) {
      return ['error' => 'Client not found.'];
    }
    if ($cliente->delete()) {
      return ['status' => true, 'data' => "Cliente deletado com sucesso"];
    } else {
      return ['error' => 'Failed to delete the client.'];
    }
  }
}
