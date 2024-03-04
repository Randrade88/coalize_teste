<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\Produto;

class ProdutoController extends ActiveController
{
    public $modelClass = 'app\models\Produto';
    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     $behaviors['authenticator'] = [
    //         'class' => HttpBearerAuth::className(),
    //     ];

    //     return $behaviors;
    // }

    public function actionIndex()
    {
        $produtos = Produto::find()->all();
        return $produtos;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass;
        $model->load(Yii::$app->request->post(), '');
        if ($model->save()) {
            return ['status' => true, 'data' => $model];
        } else {
            return ['status' => false, 'data' => $model->errors];
        }
    }
    public function actionSearchByCliente($cliente_id)
    {
        $produtos = Produto::find()->where(['cliente_id' => $cliente_id])->all();

        if (!$produtos) {
            throw new NotFoundHttpException("No products found for cliente with ID $cliente_id.");
        }

        return $produtos;
    }
    public function actionDelete($id)
    {
        $produto = $this->findModel($id);

        if ($produto->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the product.');
        }

        Yii::$app->getResponse()->setStatusCode(204); // No content
    }

    protected function findModel($id)
    {
        $produto = Produto::findOne($id);

        if ($produto === null) {
            throw new NotFoundHttpException('The requested product does not exist.');
        }

        return $produto;
    }
}
