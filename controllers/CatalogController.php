<?php

namespace app\controllers;
use app\models\AccessUserGroupMaterial;
use app\models\BaseMaterial;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use app\models\CharacteristicGroupTemp;
use app\models\ExcelReport;
use app\models\GroupSectionForm;
use app\models\SectionForm;
use roopz\imap\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CatalogController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $section = new SectionForm();
        $leaves = Catalog::ShowTreeFix();
        $leaves = json_encode($leaves);
        if (Yii::$app->user->identity->role_id == 2)
        {
            return $this->render('userindex', [
                'leaves' => $leaves,
                'section' => $section,
            ]);
        }
        else
        {
            return $this->render('index', [
                'leaves' => $leaves,
                'section' => $section,
            ]);
        }
    }
    /*
     * Создание каталога
     */
    public function actionCreate()
    {
        $model = new SectionForm();
        $id = ($_GET['id']);
        if ($model->load(Yii::$app->request->post()))
        {
            $root = Catalog::findOne(['id' => $id]);
            $subsection = new Catalog(['name' => $model->name]);
            $subsection->appendTo($root);
            return $this->redirect(['catalog/index']);
        }

        if ($id)
        {
            if (($root = Catalog::findOne(['id' => $id])) &&($root->node_type == 0))
            {
                return $this->render('create', [
                    'id' => $id,
                    'model' => $model
                ]);
            }
            else
            {
                $message = 'Выберите раздел для создания подраздела, а не группу';
                /*return $this->render('create', [
                    'message' => $message,
                    'model' => $model
                ])1*/;
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }
    /*
     * Редактирование каталога
     */
    public function actionEdit()
    {
        $model = new SectionForm();
        $id = ($_GET['id']);
        if ($model->load(Yii::$app->request->post()) )
        {
            $record = Catalog::findOne(['id' => $id]);
//            var_dump($record);
//            exit();
            $record->name = $model->name;
            $record->save();
            return $this->redirect(['catalog/index']);
        }
        if ($modelMenu = Catalog::findOne(['id' => $id]))
        {
            $model->id = $modelMenu->id;
            $model->name = $modelMenu->name;
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }
    /*
     * Создание группы
     */
    public function actionGroup()
    {
        $id = ($_GET['id']);

        $model = new GroupSectionForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $root = Catalog::findOne(['id' => $id]);
            $group = new Catalog(['name' => $model->name, 'table_name' => $model->table_name, 'node_type' => 1]);
            $group->table_name = preg_replace('/[^a-zA-Z]/', "", Yii::$app->security->generateRandomString());
            $group->appendTo($root);

            // Копируем характеристики

            $charGroupTemp = CharacteristicGroupTemp::findAll(['id_user' => Yii::$app->user->identity->id]);
            foreach ($charGroupTemp as $item)
            {
                $newGroup = new CharacteristicGroup();
                $newGroup->name = $item->name;
                $newGroup->label = preg_replace('/[^a-zA-Z]/', "", Yii::$app->security->generateRandomString());
                $newGroup->type_value = $item->type_value;
                $newGroup->is_required = $item->is_required;
                $newGroup->id_group = $group->id;
                $newGroup->save();
            }
            // -------------

            // Создаем таблицу
            BaseMaterial::createTable($group);

            // Удаляем темп
            CharacteristicGroupTemp::deleteAll(['id_user' => Yii::$app->user->identity->id]);
            // -------------
            return $this->redirect(['catalog/index']);
        }
        if (($root = Catalog::findOne(['id' => $id])) &&($root->node_type == 0))
        {
            $query = CharacteristicGroupTemp::find()->where(['id_user' => Yii::$app->user->identity->id]);
            $attributesModel = new ActiveDataProvider([
                'query' => $query
            ]);
            $model->id = $id;
            return $this->render('creategroup', [
                'attributesModel' => $attributesModel,
                'model' => $model
            ]);
        }
//        return $this->render('creategroup', [
//            'model' => $model
//        ]);
    }

    /*
     * Редактирование группы
     */
    public function actionEditgroup()
    {
        $model = new GroupSectionForm();
        $id = ($_GET['id']);
        $query = CharacteristicGroup::find()->where(['id_group' => $id]);
        $attributesModel = new ActiveDataProvider([
            'query' => $query
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $group = Catalog::findOne(['id' => $id]);
            $group->name = $model->name;
            $group->save();
            BaseMaterial::updateTable($group);
            return $this->redirect(['catalog/index']);
        }
        if (($root = Catalog::findOne(['id' => $id])) &&($root->node_type == 1))
        {
            $model->id = $id;
            $model->name = $root->name;
            return $this->render('editgroup', [
                'model' => $model,
                'attributesModel' => $attributesModel
            ]);
        }
        return $this->redirect(['catalog/index']);
    }


    //---------------------------------------------

//    public function actionDeleteAllCatalog() {
//
//        $allGroups = Catalog::findAll(['node_type'=>1]);
//        if(is_array($allGroups)) {
//
//
//
//            foreach($allGroups as $curGroup) {
//
//                if(!empty($curGroup->table_name) && !is_null($curGroup->table_name) && Yii::$app->db->schema->getTableSchema($curGroup->table_name, true) !== null) {
//                        $dd= Yii::$app->db->createCommand()->dropTable($curGroup->table_name)->execute();
//                }
//                CharacteristicGroup::deleteAll(['id_group'=>$curGroup->id]);
//                AccessUserGroupMaterial::deleteAll(['id_group_material'=>$curGroup->id]);
//                ExcelReport::deleteAll(['catalog_id'=>$curGroup->id]);
//
//            }
//
//            BaseMaterial::deleteAll();
//
//
//        }
//
//        Catalog::deleteAll('id<>:id',[':id'=>1]);
//
//
//    }

    public function actionDeletegroup()
    {
        $id = Yii::$app->request->get('id');
        $group = Catalog::findOne(['id' => $id]);
        if ($group->node_type == 0)
        {
            throw new NotFoundHttpException();
        }
        else
        {
            try{
                $result = BaseMaterial::deleteTableWithRows($group);
            }
            catch(Exception $e)
            {
                //Надо записать в лог ошибку я думаю
            }
            if ($result == 0)
                throw new NotFoundHttpException();
            $group->deleteWithChildren();
        }
        $urlToRedirect = Url::toRoute('catalog/index');
        return $this->redirect($urlToRedirect);
    }


    public function actionDeletesection()
    {
        $id = Yii::$app->request->get('id');

        $root = Catalog::findOne(['id' => $id]);
        $leaves = $root->leaves()->all();
        BaseMaterial::deleteRecursiveGroups($leaves);
        $root->deleteWithChildren();

        $urlToRedirect = Url::toRoute('catalog/index');
        return $this->redirect($urlToRedirect);
    }
    //---------------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------------
    // сверка дат
    public function actionReviseDates() {
        if (Yii::$app->user->identity->role_id == '2')
            throw new \yii\web\ForbiddenHttpException('У вас нет доступа к этому разделу');
        $model = new BaseMaterial();
        $model->load(Yii::$app->request->get());
        return $this->render('reviseDates',['model'=>$model,'filter'=>$model]);

    }

    public function actionRefreshReviseDataOfMaterial() {

        $result = ['success'=>true,'message'=>'Дата сверки обновлена'];

        $id = Yii::$app->request->get('id');

        $customer = BaseMaterial::findOne($id);
        if($customer) {
            $customer->date_verify = date('Y-m-d H:i:s');
            $customer->update();

            $result['date'] = date('d.m.Y',strtotime($customer->date_verify));
        } else {
            $result['success'] = false;
            $result['message'] = 'Материал не найден';
        }

        return json_encode($result);
    }
}