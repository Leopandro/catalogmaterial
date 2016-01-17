<?php

namespace app\models;


use yii\base\Model;

class AccessGroupsFormModel extends Model {

    public $user;
    public $accessGroups;

    public function rules()
    {
        return
            [
                ['user', 'required', 'message' => 'Выберите пользователя'],
                [['user', 'accessGroups'], 'safe']
            ];
    }

    public function attributeLabels()
    {
        return [
            'user' => 'Пользователь',
            'accessGroups'=>'Группы'
        ];
    }


} 