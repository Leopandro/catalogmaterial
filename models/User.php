<?php
namespace app\models;

use Yii;
use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{


    public static function getNameById($id) {
        $row = self::find()->andWhere('id = :userId', array('userId' => $id))->one();
        if($row)
            return $row->username;
        return '';
    }



    public static function getMenuItemsByRoleUser($isAdmin,$isGuest) {

        if($isGuest)
            return [];

        if($isAdmin) {
            return [
                ['label' => 'Пользователи', 'url' => ['/user/admin/index']],
                ['label' => 'Права доступа', 'url' => ['/access/index']],
                ['label' => 'Каталог разделов', 'url' => ['/catalog/index']],
                ['label' => 'Сверка дат', 'url' => ['/user/admin/index1']],
                ['label' => 'Импорт', 'url' => ['/user/admin/index1']],
                ['label' => 'Экспорт', 'url' => ['/user/admin/index1']],
                ['label' => '', 'url' => ['/user/admin/index1']],
                [
                    'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ];

        } else {
            return [
                ['label' => 'Каталог разделов', 'url' => ['/tasks-manager/index']],
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
