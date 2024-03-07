<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Produto;
use yii\web\UploadedFile;

class ProdutoController extends ActiveController
{
  public $modelClass = 'app\models\Produto';
  private $userId;
  public $nome;
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
    $nome = Yii::$app->request->get('nome');
    $query = Produto::find()
      ->where(['user_id' => $this->userId]);
      if ($nome !== null) {
        $query->andFilterWhere(['like', 'nome', $nome]);
    }
    $page = Yii::$app->request->get('page', 1);
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
    $model->user_id = $this->userId;
    $model->foto = UploadedFile::getInstanceByName('foto');
    if ($model->save()) {
      if ($model->foto !== null) {
        $filePath = 'uploads/' . $model->foto->baseName . '.' . $model->foto->extension;
      $model->foto->saveAs($filePath);
      $model->foto = $filePath; // Update model attribute with file path
      $model->save(false); // Save model with file path
      }
      
      return ['status' => true, 'data' => $model];
    } else {
      return ['status' => false, 'data' => $model->errors];
    }
  }

  public function actionEdits($id)
  {
    $request = Yii::$app->getRequest();
    $produto = Produto::find()
      ->where(['user_id' => $this->userId])
      ->andWhere(['id' => $id])
      ->one();

    if ($produto === null) {
      Yii::$app->response->setStatusCode(404);
      return ['error' => 'Produto não encontrado.'];
    }
    $produto->load($request->getBodyParams(), '');

    if ($produto->save()) {
      Yii::$app->response->setStatusCode(200);
      return ['status' => 'Client atualizado com sucesso.'];
    } else {
      Yii::$app->response->setStatusCode(422);
      return ['status' => false, 'error' => $produto->getErrors()];
    }
  }

  public function actionDeletes($id)
  {
    $produto = Produto::findOne($id);
    if ($produto === null) {
      return ['error' => 'Produto não encontrado'];
    }
    if ($produto->delete()) {
      return ['status' => true, 'data' => "Produto deletado com sucesso"];
    } else {
      return ['error' => 'Falha ao deletar Produto.'];
    }
  }
}
