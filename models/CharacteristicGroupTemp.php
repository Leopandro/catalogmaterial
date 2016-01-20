<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "characteristic_group_temp".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property string $name_table
 * @property integer $type_value
 * @property integer $is_required
 * @property integer $is_visible
 * @property integer $id_user
 */
class CharacteristicGroupTemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'characteristic_group_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_value', 'is_required', 'is_visible', 'id_user'], 'integer'],
            [['id_user', 'name'], 'required'],
            [['name', 'label'], 'string', 'max' => 255]
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Имя аттрибута',
            'label' => 'Описание',
            'type_value' => 'Тип значения',
            'is_required' => 'Обязательность',
            'id_group' => 'Id Group',
            'is_visible' => 'Видимость',
        ];
    }
}
