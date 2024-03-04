<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\rest\ActiveController;
use app\models\User;

class UserController extends Controller
{
    public $modelClass = 'app\models\User';

    public function actionCreate()
    {
        $model = new $this->modelClass;
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

}
