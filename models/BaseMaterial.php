<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

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

}
