<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 13.01.2016
 * Time: 14:32
 */

namespace app\models;

use yii\base\Model;

class GroupSectionForm extends Model
{
    public $name;
    public $table_name;
    public $id;

    public function rules()
    {
        return
            [
                ['id', 'required'],
                ['name', 'trim'],
                ['name', 'required', 'message' => 'Введите имя группы'],
                ['name', 'string', 'max' => 255, 'message' => 'Максимальная длина 255 символов'],
                ['name', 'string', 'min' => 1],
                [['id', 'name', 'table_name'], 'safe']
            ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название группы',
            'table_name' => 'Описание группы'
        ];
    }
}

?>