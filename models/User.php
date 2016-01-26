<?php
namespace app\models;

use Yii;
use dektrium\user\models\User as BaseUser;
use yii\helpers\ArrayHelper;


class User extends BaseUser
{

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    public static function getAllUsersForDropDownListByRole($roleName) {

        $roleData = Role::findOne(["name"=>$roleName]);

        $data = [];
        if($roleData)
            $data = ArrayHelper::map(User::find()->where(["role_id"=>$roleData->id])->asArray()->all(),'id','username');

        return $data;
    }

    public static function getNameById($id) {
        $row = self::find()->andWhere('id = :userId', array('userId' => $id))->one();
        if($row)
            return $row->username;
        return '';
    }



    public static function getMenuItemsByRoleUser($isAdmin,$isGuest) {

        if($isGuest)
            return [];

        if(Yii::$app->user->identity->role_id == User::ROLE_ADMIN) {
            return [
                ['label' => 'Пользователи', 'url' => ['/user/admin/index']],
                ['label' => 'Права доступа', 'url' => ['/access/index']],
                ['label' => 'Каталог разделов', 'url' => ['/catalog/index']],
//                ['label' => 'Атрибуты', 'url' => ['/attributes/index']],
                ['label' => 'Сверка дат', 'url' => ['/catalog/revise-dates']],
                ['label' => 'Импорт', 'url' => ['/user/admin/index1']],
                ['label' => 'Экспорт', 'url' => ['/user/admin/index1']],
                ['label' => '', 'url' => ['/user/admin/index1']],
                [
                    'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ];

        } else if(Yii::$app->user->identity->role_id == User::ROLE_USER){
            return [
                ['label' => 'Каталог разделов', 'url' => ['/catalog/index']],
                ['label' => 'Каталог материалов', 'url' => ['/tasks-manager/index']],
                ['label' => 'Отчет', 'url' => ['/tasks-manager/index']],
                [
                    'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ];
        }
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
