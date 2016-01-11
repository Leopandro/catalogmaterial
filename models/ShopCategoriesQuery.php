<?php

namespace app\models;

use paulzi\adjacencylist\AdjacencyListQueryTrait;

class ShopCategoriesQuery extends \yii\db\ActiveQuery
{
    use AdjacencyListQueryTrait;
}