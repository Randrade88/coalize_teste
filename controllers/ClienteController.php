<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\Cliente;

class ClienteController extends ActiveController
{
    public $modelClass = 'app\models\Cliente';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        return ['message' => 'This is the index action of ClienteController'];
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
}
