<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 12.01.2016
 * Time: 15:17
 */
namespace app\models;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;

class Catalog extends ActiveRecord
{
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CatalogQuery(get_called_class());
    }
}