<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\mysql\Schema;
use yii\db\Query;
/**
 * This is the model class for table "base_material".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_verify
 * @property string $source
 * @property string $code
 * @property string $note
 */
class BaseMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_verify'], 'safe'],
            [['name', 'source', 'code', 'note'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'date_verify' => 'Дата верификации',
            'source' => 'Источник',
            'code' => 'Шифр',
            'note' => 'Примечание',
        ];
    }

    public static function getAttributesArray()
    {
        return [
            ['Наименование'],
            ['Дата верификации'],
            ['Источник'],
            ['Шифр'],
            ['Примечание'],
        ];
    }

    public function search() {


        $query = self::find();

        if($this->date_verify) {
            $query->where( [
                'OR',['<', 'date_verify', date("Y-m-d 00:00:00",strtotime($this->date_verify))],['date_verify'=>null]
            ]);
        }


        return new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort'=>[
                'attributes'=>[
                    'name',
                    'note',
                    'source',
                    'code',
                    'date_verify'
                ]
            ]

        ]);
    }

    public static function updateTable($group)
    {
        $columns = CharacteristicGroup::findAll(['id_group' => $group->id]);
        $sql = Yii::$app->db->createCommand();

        //Проверка есть ли в таблице столбец с заданным label-ом
        //если нет - добавляем
        foreach ($columns as $column)
        {
            if (!$thiscolumn = Yii::$app->db->schema->getTableSchema($group->table_name)->columns[$column->label])
            {
                if ($column->type_value == 0)
                    $valueType = Schema::TYPE_INTEGER.'(11) NOT NULL';
                elseif ($column->type_value == 1)
                    $valueType = Schema::TYPE_STRING.'(255) NOT NULL';
                elseif ($column->type_value == 2)
                    $valueType = Schema::TYPE_DECIMAL.'(11,2) NOT NULL';
                else
                    $valueType = Schema::TYPE_STRING.'(255) NOT NULL';
                $sql->addColumn($group->table_name, $column->label, $valueType)->execute();
            }
            else
            {
                $type = $thiscolumn->type; // decimal string integer
                $columntype = $column->type_value; // 0 = integer 1 = string 2 = decimal
                if ($columntype == 0)
                {
                    $columntype = 'integer';
                    $sqlType = Schema::TYPE_INTEGER.'(11) NOT NULL';
                }
                if ($columntype == 1)
                {
                    $columntype = 'string';
                    $sqlType = Schema::TYPE_STRING.'(255) NOT NULL';
                }
                if ($columntype == 2)
                {
                    $columntype = 'decimal';
                    $sqlType = Schema::TYPE_DECIMAL.'(11,2) NOT NULL';
                }
                if ($type != $columntype)
                {
                    //поменять на $columntype
                    $sql->alterColumn($group->table_name, $column->label, $sqlType)->execute();
                    $x = 1;
                }
            }
        }
        $columnsInTable = Yii::$app->db->schema->getTableSchema($group->table_name)->getColumnNames();

        //Проверка, есть ли такой столбец в списке характеристик
        //Если нет - удаляем
        foreach($columnsInTable as $columnInTable)
        {
            if ($columnInTable != 'id')
            {
                $haveColumn = false;
                foreach ($columns as $column)
                {
                    if ($columnInTable == $column->label)
                        $haveColumn = true;
                }
                if ($haveColumn == false)
                {
                    $sql->dropColumn($group->table_name, $columnInTable)->execute();
                }
            }
        }
        //Проверка соответствия типов
    }

    public static function createTable($group)
    {
        $columns = CharacteristicGroup::findAll(['id_group' => $group->id]);
        // Создаем таблицу
        $sql = Yii::$app->db->createCommand();
        $sql->createTable($group->table_name, [
            'id' => 'pk',
        ])->execute();

        //Добавляем колонки
        foreach ($columns as $column)
        {
            $columnName = Yii::$app->security->generateRandomString(11);
            if ($column->type_value == 0)
                $valueType = Schema::TYPE_INTEGER.'(11) NOT NULL';
            elseif ($column->type_value == 1)
                $valueType = Schema::TYPE_STRING.'(255) NOT NULL';
            elseif ($column->type_value == 2)
                $valueType = Schema::TYPE_DECIMAL.'(11,2) NOT NULL';
            else
                $valueType = Schema::TYPE_STRING.'(255) NOT NULL';
            $sql->addColumn($group->table_name, $columnName, $valueType)->execute();
        }

        //и связываем id c id base_material
        $sql->addForeignKey(Yii::$app->security->generateRandomString(), $group->table_name, 'id', 'base_material', 'id', 'NO ACTION', 'NO ACTION')
            ->execute();
    }

    /* Получаем id записей из таблицы(сгенерированной)*/
    public static function getIds($table_name)
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
    public static function getLabels($group)
    {
        $labels = (new Query())
            ->select('name,label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        return $labels;
    }

    /* Получаем строку из сгенерированной таблицы по id*/
    public static function getRow($columns, $group, $id)
    {
        $rows = (new Query())
            ->select($columns)
            ->from($group->table_name)
            ->where(['id' => $id])
            ->one();
        return $rows;
    }

    /* Получаем все строки из сгенерированной таблицы*/
    public static function getRows($group)
    {
        $rows = (new Query())
            ->select("*")
            ->from($group->table_name)
            ->all();
        return $rows;
    }

    /* Получаем строки из base_material */
    public static function getMaterials($ids)
    {
        $rows = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where(['id' => $ids])
            ->all();
        return $rows;
    }
    /* Получить характеристики экземпляра base_material*/
    public static function getModel($group, $material_id)
    {
        $labels = self::getLabels($group);
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }
        $basematerial = BaseMaterial::find()->where(['id' => $material_id])->one();
        foreach ($basematerial as $key => $value)
        {
            $obj[$key] = $value;
        }
        $rows = self::getRow($columns, $group, $material_id);
        foreach ($labels as $label) {
            $obj[$label['label']] = $rows[$label['label']];
        }
        return ($model = (Object)$obj);
    }

    /* Получаем таблицу из таблицы материалов и характеристик для xsl*/
    public static function getModels($group)
    {
        $arr = self::getAttributesArray();
        $ids = self::getIds($group->table_name);
        $materials = self::getMaterials($ids);
        foreach ($materials as $material)
        {
            $material = array_values($material);
            for ($i = 1; $i < count($material); $i++)
            {
                $arr[$i-1][] = $material[$i];
            }
        }
        $labels = self::getLabels($group);
        $rows = self::getRows($group);
        $i = 1;
        foreach ($labels as $label)
        {
            $arr[$i+5][] = $label['name'];
            $i++;
        }

        foreach ($rows as $row)
        {
            $row = array_values($row);
            for ($i = 0; $i < count($row) - 1;$i++)
            {
                $j = $i + 6;
                $arr[$j][] = $row[$i+1];
            }
        }
        return $arr;
    }

    /* Получаем колонки с именами и лейблами */
    public static function getColumnsAndLabels($group)
    {
        $columns = self::getBaseMaterialLabels();
        $labelsCharacteritic = self::getLabels($group);
        $i = 4;
        foreach ($labelsCharacteritic as $label) {
            $columns[$i]['label'] = $label['name'];
            $columns[$i]['attribute'] = $label['label'];
            $i++;
        }
        return $columns;
    }

    /* Массив атрибутов и лейблов для detailview */
    public static function getBaseMaterialLabels()
    {
        return [
            [
                'attribute' => 'name',
                'label' => 'Наименование'
            ],
            [
                'attribute' => 'date_verify',
                'label' => 'Дата верификации'
            ],
            [
                'attribute' => 'source',
                'label' => 'Источник'
            ],
            [
                'attribute' => 'code',
                'label' => 'Шифр'
            ],
            [
                'attribute' => 'note',
                'label' => 'Примечание'
            ],
        ];
    }
}
