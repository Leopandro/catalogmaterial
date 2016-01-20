<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroupTemp */

$this->title = 'Редактировать характеристику группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-group-temp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
