<?php

namespace app\controllers;

use app\models\BaseMaterial;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use Yii;
use app\models\BaseMaterial2;
use app\models\BaseMaterialSearch;
use yii\base\Object;
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

    public function actionModel()
    {
        if (Yii::$app->request->isAjax)
        {
            $group_id = $_GET['id'];
            $group_id = 89;
            $material_id = $_GET['material_id'];
            $material_id = 1;
            $group = Catalog::findOne(['id' => $group_id]);
            $model = $this->getModel($group, $material_id);
            return json_encode(['model' => $model]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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

    protected function getLabels($group)
    {
        $labels = (new Query())
            ->select('name,label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        return $labels;
    }

    protected function getRows($columns, $group, $id)
    {
        $rows = (new Query())
            ->select($columns)
            ->from($group->table_name)
            ->where(['id' => $id])
            ->one();
        return $rows;
    }

    protected function getModel($group, $id)
    {
        $labels = $this->getLabels($group);
        $columns[] = 'id';
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }
        $rows = $this->getRows($columns, $group, $id);
        $obj = [];
        $columns = [];
//        $columns[0]['attribute'] = 'id';
//        $columns[0]['label'] = 'id';
//        $obj['id'] = 'id';
        $i = 1;
        foreach ($labels as $label) {
            $columns[$i]['label'] = $label['name'];
            $columns[$i]['attribute'] = $label['label'];
            $obj[$label['label']] = $rows[$label['label']];
            $i++;
        }
        return ($model = (Object)$obj);
    }
    protected function getColumns($group)
    {
        $labels = $this->getLabels($group);
        $columns[] = 'id';
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }
    }
}
