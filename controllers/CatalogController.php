<?php

namespace app\controllers;
use app\models\Catalog;
use app\models\CharacteristicGroup;
use app\models\CharacteristicGroupSearch;
use app\models\CharacteristicGroupTemp;
use app\models\GroupSectionForm;
use app\models\SectionForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use Yii;



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
        $leaves = Catalog::find()->all();
        /*
         * init root
         */
        $sorted = false;
        $length = count($leaves) - 1;
        //
        while ($sorted == false)
        {
            $sorted = true;
            for ($i = 0; $i < $length; $i++)
            {
                if ($leaves[$i]->name > $leaves[$i+1]->name)
                {
                    $k = $leaves[$i];
                    $leaves[$i] = $leaves[$i+1];
                    $leaves[$i+1] = $k;
                    $sorted = false;
                }
            }
        }

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


    /*
     * Создание каталога
     */
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
            $group->appendTo($root);

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

    public function actionEditgroup()
    {
        $model = new GroupSectionForm();
        $id = ($_GET['id']);
//        $searchModel = new CharacteristicGroupSearch();
//        $attributesModel = $searchModel->search(['id_group' => $id]);
        $query = CharacteristicGroup::find()->where(['id_group' => $id]);
        $attributesModel = new ActiveDataProvider([
            'query' => $query
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $group = Catalog::findOne(['id' => $id]);
            $group->name = $model->name;
            $group->save();
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

//    public function translit($str)
//    {
//        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С',
// 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з',
// 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю',
// 'я', ' ');
//        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S',
// 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh',
// 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y',
// 'e', 'yu', 'ya', '_');
//        return str_replace($rus, $lat, $str);
//    }
}