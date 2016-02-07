<?php

namespace app\models;


use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class AccessGroupsFormModel extends Model {

    public $user;
    public $accessGroups;

    public $idsAllowAccessGroupsMaterial = [];

    public function rules()
    {
        return
            [
               // ['user', 'required', 'message' => 'Выберите пользователя'],
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


    public function loadTreeDataAccessGroups() {

        $query = new Query;
        $query->select('id_group_material as id')
            ->from('access_user_group_material')
            ->where(['id_user'=>$this->user,'is_allow'=>1]);

        $rows = $query->all();
        $this->idsAllowAccessGroupsMaterial = array_keys(ArrayHelper::map($rows,'id','id'));

        $this->accessGroups = implode($this->idsAllowAccessGroupsMaterial,',');
    }

} 