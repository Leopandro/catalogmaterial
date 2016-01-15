<?php

namespace app\controllers;
use app\models\Catalog;
use app\models\SectionForm;
use Yii;

class CatalogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $section = new SectionForm();
        $leaves = Catalog::find()->all();
        /*
         * init root
         */
        if (!$leaves)
        {
            $root = new Catalog(['name' => 'Разделы']);
            $root->makeRoot();
            $leaves = Catalog::find()->all();
        }
        return $this->render('index', [
            'leaves' => $leaves,
            'section' => $section
        ]);
    }

    public function actionCreate()
    {
        $model = new SectionForm();
        $id = ($_GET['id']);
        if ($model->load(Yii::$app->request->post()))
        {
            $root = Catalog::findOne(['id' => $model->id]);
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
                return $this->render('create', [
                    'message' => $message,
                    'model' => $model
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

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
}
