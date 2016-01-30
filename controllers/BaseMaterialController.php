<?php

namespace app\controllers;

use app\models\AccessUserGroupMaterial;
use app\models\BaseMaterial;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use app\models\ExcelReport;
use app\models\ExcelReports;
use app\models\ImportFromExcel;
use app\models\ImportModel;
use app\models\ImportModelForm;
use app\models\UploadForm;
use Yii;
use app\models\BaseMaterial2;
use app\models\BaseMaterialSearch;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\DynamicFormMaterial;
use yii\base\DynamicModel;use yii\web\UploadedFile;
use PHPExcel;
use PHPExcel_Style_Alignment;

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
            if (Yii::$app->user->identity->role_id == 1)
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
            else
            {
                $group = Catalog::findOne(['id' => $id]);
                if (AccessUserGroupMaterial::findOne(['id_group_material' => $id, 'id_user' => Yii::$app->user->identity->id]))
                {
                    $group = Catalog::findOne(['id' => $id]);

                    // берем все id из сгенерированной таблицы для поиска по base_material */
                    $ids = BaseMaterial::getIds($group->table_name);

                    // поиск по всем id из base_material
                    $dataProvider = new ActiveDataProvider([
                        'query' => BaseMaterial::find()->where(['id' => $ids])
                    ]);
                    return $this->render('userindex', [
                        'group_name' => $group->name,
                        'group_id' => $id,
                        'dataProvider' => $dataProvider
                    ]);
                }
                else
                {
                    $message = 'У вас нет доступа к этому разделу';
                    return $this->render('userindex', [
                        'message' => $message
                    ]);
                }
            }
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddreport()
    {
        $group_id = Yii::$app->request->get('group_id');
        $material_id = Yii::$app->request->get('id');
        $user_id = Yii::$app->user->identity->id;
        if (Yii::$app->request->isAjax) {
            if (
                $row = (new Query())
                ->select('id')
                ->from(ExcelReport::tableName())
                ->where(['user_id' => $user_id, 'catalog_id' => $group_id, 'base_material_id' => $material_id])
                ->one()
            )
                return "<div class=\"alert alert-warning\">"."Этот материал уже добавлен в отчет"."</div>";
            else
            {
                $row = (new Query())
                    ->createCommand()
                    ->insert(ExcelReport::tableName(), ['user_id' => $user_id, 'catalog_id' => $group_id, 'base_material_id' => $material_id])
                    ->execute();
                return "<div class=\"alert alert-success\">"."Добавлено в отчет"."</div>";
            }
        }
    }

    public function actionReport()
    {
        //$id_group = Yii::$app->request->get('group_id');

        if (Yii::$app->user->identity->role_id == 2) {
            BaseMaterial::getExcelReport();
        }
        $this->renderAjax('index');
    }

    public function actionImport()
    {
        $id = Yii::$app->request->get('id');
        $catalog = Catalog::findOne(['id' => $id]);
        $importModel = new ImportModel();
        $importModel->generateSettings($id);

        if ($importModel->load(Yii::$app->request->post()))
        {
            $importModel->file = UploadedFile::getInstance($importModel, 'file');
            if ($filename = $importModel->upload())
            {
                $import = new ImportFromExcel();
                $import->settings = $importModel->columnSettings;
                $import->id_group = $id;
                //$import->startRow = $importModel->startRow;
                $import->filename = $filename;
                $import->import();
            }
        }
        return $this->render('import', [
            'catalog' => $catalog,
            'importModel' => $importModel,
        ]);
    }

    // вывод в excel
    public function actionExcel()
    {
        /* Находим группу по id */
        $group_id = $_GET['id'];
        $group = Catalog::findOne(['id' => $group_id]);
        //получаем таблицу значений полей таблицы материала и характеристик
        $model = BaseMaterial::getModels($group);

        //установка ширины
        $xls = new \PHPExcel();
        $xls->setActiveSheetIndex(0);
        $xls->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $xls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $xls->getActiveSheet()->getColumnDimension('C')->setWidth(22);
        $xls->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $xls->getActiveSheet()->getColumnDimension('E')->setWidth(22);

        //перевод строки
        foreach (range('A','Z') as $columnName){
            $xls->getActiveSheet()->getStyle($columnName)->getAlignment()->setWrapText(true);
            $xls->getActiveSheet()->getStyle($columnName)->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $xls->getActiveSheet()->getStyle($columnName)->getAlignment()->setVertical(
                PHPExcel_Style_Alignment::VERTICAL_TOP);
        }
        //установка ширины
        foreach (range('F','Z') as $columnName){
            $xls->getActiveSheet()->getColumnDimension($columnName)->setWidth(15);
        }
        //импорт данных из массива $model
        $xls->getActiveSheet()->fromArray($model, null, 'A1');

        //Вывод excel файла
        $filename = 'ImportGroup_'.Yii::$app->user->identity->username.'_'.date('d-m-Y_H:i:s', time());
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename.'.xls');
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

        if (Yii::$app->user->identity->role_id == 2)
        {
            return false;
        }
        $modelForm = new DynamicFormMaterial(Yii::$app->request->get('group_id'));
        if(Yii::$app->request->get('id'))
            $modelForm->id = Yii::$app->request->get('id');
        if(Yii::$app->request->get('group_id'))
            $modelForm->group_id = Yii::$app->request->get('group_id');

        $modelForm->loadDataMaterial();


        if($modelForm->load(Yii::$app->request->post()) && DynamicModel::validateData($modelForm->attributes,$modelForm->rules())) {

            BaseMaterial::updateModel($modelForm->group_id,$modelForm->id,$modelForm->attributes);
            $urlToRedirect = Url::toRoute(["/basematerial/index",'id' => Yii::$app->request->get('group_id')]);
            $this->redirect($urlToRedirect);

        }

        return $this->render('update', [
            'modelForm' => $modelForm
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
