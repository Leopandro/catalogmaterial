<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 12.01.2016
 * Time: 15:18
 */
namespace app\models;
use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class CatalogQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}