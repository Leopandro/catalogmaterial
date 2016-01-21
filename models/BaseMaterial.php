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

        //Проверка изменений
        foreach ($columns as $column)
        {
            if (!Yii::$app->db->schema->getTableSchema($group->table_name)->columns[$column->label])
            {
                $sql->addColumn($group->table_name, $column->label, Schema::TYPE_STRING. '(255) NOT NULL')->execute();
            }
        }
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
            $columnName = StringUtils::translit($column->name);
            $sql->addColumn($group->table_name, $columnName, Schema::TYPE_STRING.' NOT NULL')->execute();
        }

        //и связываем id c id base_material
        $sql->addForeignKey(Yii::$app->security->generateRandomString(), $group->table_name, 'id', 'base_material', 'id', 'NO ACTION', 'NO ACTION')
            ->execute();
    }
}
