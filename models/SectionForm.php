<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 13.01.2016
 * Time: 14:32
 */

namespace app\models;

use yii\base\Model;

class SectionForm extends Model
{
    public $name;
    public $id;

    public function rules()
    {
        return
            [
                ['id', 'required'],
                ['name', 'trim'],
                ['name', 'required', 'message' => 'Введите имя раздела/группы'],
                ['name', 'string', 'max' => 255, 'message' => 'Максимальная длина 255 символов'],
                ['name', 'string', 'min' => 1],
                [['id', 'name'], 'safe']
            ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя раздела/группы'
        ];
    }
}

?>