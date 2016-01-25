<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BaseMaterial2 */

$this->title = 'Редактирование материала';
$group_id = $_GET['group_id'];
?>
<div class="base-material2-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <p>id = <? echo $id?></p>
    <p> group id = <? echo $group_id?></p>
</div>
