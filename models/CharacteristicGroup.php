<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "characteristic_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property string $name_table
 * @property integer $type_value
 * @property integer $is_required
 * @property integer $id_group
 * @property integer $is_visible
 *
 * @property Catalog $idGroup
 */
class CharacteristicGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'characteristic_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_value', 'is_required', 'id_group', 'is_visible'], 'integer'],
            [['name', 'label', 'name_table'], 'string', 'max' => 255]
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
            'label' => 'Label',
            'name_table' => 'Name Table',
            'type_value' => 'Тип значения',
            'is_required' => 'Обязателен?',
            'id_group' => 'Id Group',
            'is_visible' => 'Скрытый?',
        ];
    }

    public static function getDataForDropDownList()
    {
        return [
            '0' => 'Любое',
            '1' => 'Числовое',
            '2'=> 'Текст'
        ];
    }
    public static function getLabelForRequired()
    {
        return [
            '0' => 'Обязателен',
            '1' => 'Не обязателен'
        ];
    }

    public static function getLabelForHidden()
    {
        return [
            '0' => 'Видимый',
            '1' => 'Скрытый'
        ];
    }

    public static function getLabelTypeById($id)
    {
        $data = self::getDataForDropDownList();
        if (array_key_exists($id, $data))
            return $data[$id];
        return 'Не задано';
    }

    public static function getLabelForRequiredById($id)
    {
        $data = self::getLabelForRequired();
        if (array_key_exists($id, $data))
            return $data[$id];
        return 'Не задано';
    }

    public static function getLabelForHiddenById($id)
    {
        $data = self::getLabelForHidden();
        if (array_key_exists($id, $data))
            return $data[$id];
        return 'Не задано';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroup()
    {
        return $this->hasOne(Catalog::className(), ['id' => 'id_group']);
    }
}
