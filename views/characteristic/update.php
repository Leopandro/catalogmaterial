<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroup */

$this->title = 'Update Characteristic Group: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristic Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
