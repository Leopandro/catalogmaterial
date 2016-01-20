<?php

namespace app\controllers;
use app\models\AccessUserGroupMaterial;
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
        //$leaves = Catalog::find()->all();
        $allUsersWithRoleUser = User::getAllUsersForDropDownListByRole("user");

        $modelForm = new AccessGroupsFormModel;
        if (Yii::$app->request->get('idUser')) {
            $modelForm->user = Yii::$app->request->get('idUser');
            $modelForm->loadTreeDataAccessGroups();
        }

        if ($modelForm->load(Yii::$app->request->post()))
        {
            AccessUserGroupMaterial::deleteAll(['id_user'=>$modelForm->user]);
            $idsGroupsIsAllow = explode(',',$modelForm->accessGroups);
            $idsGroupsIsAllow = Catalog::checkAndGetIdsOfGroupElements($idsGroupsIsAllow);
            $dataBatch = [];
            foreach(is_array($idsGroupsIsAllow) ? $idsGroupsIsAllow : []  as $cur_id )
                $dataBatch[] = ['id_user'=>$modelForm->user,'id_group_material'=>$cur_id,'is_allow'=>1];

            if(count($dataBatch)>0)
                Yii::$app->db->createCommand()->batchInsert(AccessUserGroupMaterial::tableName(), ['id_user','id_group_material','is_allow'], $dataBatch)->execute();

            $modelForm->loadTreeDataAccessGroups();

            \Yii::$app->session->setFlash('success','Данные сохранены');
        }



        return $this->render('index',['users'=>$allUsersWithRoleUser,'modelForm'=>$modelForm]);
    }

}
