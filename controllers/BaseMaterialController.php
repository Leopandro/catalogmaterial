<?php

namespace app\controllers;

use app\models\BaseMaterial;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use app\models\UploadForm;
use Yii;
use app\models\BaseMaterial2;
use app\models\BaseMaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\DynamicFormMaterial;
use yii\web\UploadedFile;
use PHPExcel;

/**
 * BaseMaterialController implements the CRUD actions for BaseMaterial2 model.
 */
class BasematerialController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all BaseMaterial2 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseMaterialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($id = Yii::$app->request->get('id'))
        {
            $group = Catalog::findOne(['id' => $id]);

            // берем все id из сгенерированной таблицы для поиска по base_material */
            $ids = BaseMaterial::getIds($group->table_name);

            // поиск по всем id из base_material
            $dataProvider = new ActiveDataProvider([
                'query' => BaseMaterial::find()->where(['id' => $ids])
            ]);

            return $this->render('index', [
                'group_name' => $group->name,
                'group_id' => $id,
                'dataProvider' => $dataProvider
            ]);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {

    }

    // вывод в excel
    public function actionExcel()
    {
        /* Находим группу по id */
        $group_id = $_GET['id'];
        $group = Catalog::findOne(['id' => $group_id]);
        //получаем таблицу значений полей таблицы материала и характеристик
        $model = BaseMaterial::getModels($group);

        $xls = new \PHPExcel();
        $xls->setActiveSheetIndex(0);
        $xls->getActiveSheet()->fromArray($model, null, 'A1');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="your_name.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        $writer->save('php://output');
    }

    // Получаем характеристики по ajax

    public function actionModel()
    {
        if (Yii::$app->request->isAjax)
        {
            $group_id = $_GET['id'];
            $material_id = $_GET['material_id'];
            $group = Catalog::findOne(['id' => $group_id]);
            $columns = BaseMaterial::getColumnsAndLabels($group);
            $model = BaseMaterial::getModel($group, $material_id);
            return $this->renderAjax(
                '_detailview',
                [
                    'model' => $model,
                    'columns' => $columns
                ]
            );
        }
    }
    /**
     * Displays a single BaseMaterial2 model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BaseMaterial2 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseMaterial2();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BaseMaterial2 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

//        $modelForm = new DynamicFormMaterial();
//        if(Yii::$app->request->get('id'))
//            $modelForm->id = Yii::$app->request->get('id');
//
//
//        $modelForm->loadDataMaterial();


        return $this->render('update', [
            'id' => $id
        ]);
    }

    /**
     * Deletes an existing BaseMaterial2 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BaseMaterial2 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseMaterial2 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseMaterial2::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
