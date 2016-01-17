<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\AccessGroupsFormModel;
use app\models\Catalog;

class AccessController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['index'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action){
                            return Yii::$app->user->identity->role_id == User::ROLE_ADMIN;
                        },
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?','@'],
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {

        $leaves = Catalog::find()->all();
        $allUsersWithRoleUser = User::getAllUsersForDropDownListByRole("user");

        $modelForm = new AccessGroupsFormModel;


        if (Yii::$app->request->get('id'))
        {
            return $this->render('index', ['id' => $_GET['id']]);
        }



        return $this->render('index',['users'=>$allUsersWithRoleUser,'modelForm'=>$modelForm,'leaves'=>$leaves]);
    }

}
