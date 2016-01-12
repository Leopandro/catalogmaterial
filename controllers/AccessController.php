<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;

class AccessController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->get('id'))
        {
            return $this->render('index', ['id' => $_GET['id']]);
        }
        return $this->render('index');
    }

}
