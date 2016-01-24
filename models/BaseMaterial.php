<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\mysql\Schema;
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

    public function search() {


        $query = BaseMaterial::find();

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
}
