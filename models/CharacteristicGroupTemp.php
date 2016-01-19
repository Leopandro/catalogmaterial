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
            [['id_user'], 'required'],
            [['name', 'label', 'name_table'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'label' => 'Label',
            'name_table' => 'Name Table',
            'type_value' => 'Type Value',
            'is_required' => 'Is Required',
            'is_visible' => 'Is Visible',
            'id_user' => 'Id User',
        ];
    }
}
