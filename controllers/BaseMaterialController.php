<?php

namespace app\controllers;

use app\models\BaseMaterial;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use Yii;
use app\models\BaseMaterial2;
use app\models\BaseMaterialSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
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
        if ($_GET['id'])
        {
            $group = Catalog::findOne(['id' => $_GET['id']]);

            /* берем все id из сгенерированной таблицы для поиска по base_material */
            $ids = $this->getIds($group->table_name);

            /* Берем имена колонок и колонки из таблицы характеристик*/
            $columns = $this->getColumns($group);

            $id = 0;
            /* Модель для гридвью */
            $model = $this->getModel($group, $id);

            /* поиск по всем id из $table_name */
            $dataProvider = new ActiveDataProvider([
                'query' => BaseMaterial::find()->where(['id' => $ids])
            ]);
            return $this->render('index', [
                'model' => $model,
                'columns' => $columns,
                'dataProvider' => $dataProvider
            ]);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // вывод в excel
    public function actionExcel()
    {
//        $xls = new \PHPExcel();
//        $xls->setActiveSheetIndex(0);
//        // Получаем активный лист
//        $sheet = $xls->getActiveSheet();
        // Подписываем лист

        /* Находим группу по id */
        $group_id = $_GET['id'];
        //----------------------------------------------

        $group = Catalog::findOne(['id' => $group_id]);

        //получаем таблицу значений полей таблицы характеристик
        $model = $this->getModels($group);

        //----------------------------------------------
        return $this->render(
            '_exceltest',
            [
                'model' => $model,
            ]
        );
    }

    // Получаем характеристики по ajax

    public function actionModel()
    {
        if (Yii::$app->request->isAjax)
        {
            $group_id = $_GET['id'];
            $material_id = $_GET['material_id'];
            $group = Catalog::findOne(['id' => $group_id]);
            $columns = $this->getColumns($group);
            $model = $this->getModel($group, $material_id);
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

    /* Получаем id записей из таблицы(сгенерированной)*/
    protected function getIds($table_name)
    {
        $rows = (new Query())
            ->select('id')
            ->from($table_name)
            ->all();
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    /* Получаем имена и лейблы из таблицы характеристик групп по id группы*/
    protected function getLabels($group)
    {
        $labels = (new Query())
            ->select('name,label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        return $labels;
    }

    /* Получаем строку из сгенерированной таблицы по id*/
    protected function getRow($columns, $group, $id)
    {
        $rows = (new Query())
            ->select($columns)
            ->from($group->table_name)
            ->where(['id' => $id])
            ->one();
        return $rows;
    }

    /* Получаем все строки из сгенерированной таблицы*/
    protected function getRows($group)
    {
        $rows = (new Query())
            ->select("*")
            ->from($group->table_name)
            ->all();
        return $rows;
    }

    /* Получить характеристики экземпляра base_material*/
    protected function getModel($group, $id)
    {
        $labels = $this->getLabels($group);
        $columns[] = 'id';
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }
        $rows = $this->getRow($columns, $group, $id);
        $obj = [];
        $i = 1;
        foreach ($labels as $label) {
            $obj[$label['label']] = $rows[$label['label']];
            $i++;
        }
        return ($model = (Object)$obj);
    }

    protected function getModels($group)
    {
        $arr = [];
        $i = 0;
        $labels = $this->getLabels($group);
        $rows = $this->getRows($group);
        foreach ($labels as $label)
        {
            $arr[$i][] = $label['name'];
            $i++;
        }

        foreach ($rows as $row)
        {
            $row = array_values($row);
            for ($i = 0; $i < count($arr);$i++)
            {
                $arr[$i][] = $row[$i+1];
            }
        }
        return $arr;
    }

    /* Получаем колонки с именами и лейблами */
    protected function getColumns($group)
    {
        $labels = $this->getLabels($group);
        $columns[] = 'id';
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }

        $columns = [];
        $i = 1;
        foreach ($labels as $label) {
            $columns[$i]['label'] = $label['name'];
            $columns[$i]['attribute'] = $label['label'];
            $i++;
        }
        return $columns;
    }
}
