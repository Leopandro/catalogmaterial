<?php

namespace app\models;

use Yii;
use app\models\StringUtils;
/**
 * This is the model class for table "characteristic_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $type_value
 * @property integer $is_required
 * @property integer $id_group
 * @property integer $is_visible
 *
 * @property Catalog $idGroup
 */
class CharacteristicGroup extends \yii\db\ActiveRecord
{
    const type_integer = 0;
    const type_string = 1;
    const type_decimal = 2;
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
            'name' => 'Имя атрибута',
            'label' => 'Описание',
            'type_value' => 'Тип значения',
            'is_required' => 'Обязательность',
            'id_group' => 'Id Group',
            'is_visible' => 'Видимость',
        ];
    }

    public static function getDataForDropDownList()
    {
        return [
            self::type_integer => 'Число', //0
            self::type_string => 'Текст', //1
            self::type_decimal => 'Дробное число' //2
        ];
    }
    public static function getDataForDropDownListString()
    {
        return [
            self::type_string => 'Текст'
        ];
    }
    public static function getDataForDropDownListInteger()
    {
        return [
            self::type_integer => 'Число',
            self::type_string => 'Текст',
            self::type_decimal => 'Дробное число'
        ];
    }
    public static function getDataForDropDownListDecimal()
    {
        return [
            self::type_integer => 'Число',
            self::type_string => 'Текст',
            self::type_decimal => 'Дробное число'
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

    public function beforeSave($insert)
    {
        if ($this->label == '')
        {
            $this->label = Yii::$app->security->generateRandomString(11);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
