<?php
namespace app\models;

use dektrium\user\models\User as BaseUser;
use Yii;
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
                ['label' => 'Пользователи', 'url' => ['/users/index']],
                ['label' => 'Права доступа', 'url' => ['/tasks/index']],
                ['label' => 'Каталог разделов', 'url' => ['/shop-categories/index']],
                ['label' => 'Каталог материалов', 'url' => ['/shop-products/index']],
                ['label' => 'Добавление раздела', 'url' => ['/shop-products/tree']],
                ['label' => 'Группа', 'url' => ['/clients/index']],
                ['label' => 'Характеристика группы', 'url' => ['/user/admin/index']],
                ['label' => 'Карточка материала', 'url' => ['/user/admin/index']],
                ['label' => 'Сверка дат', 'url' => ['/user/admin/index']],
                ['label' => 'Импорт', 'url' => ['/user/admin/index']],
                ['label' => 'Экспорт', 'url' => ['/user/admin/index']],
                ['label' => '', 'url' => ['/user/admin/index']],
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

}
