<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroupTemp */

$this->title = 'Update Characteristic Group Temp: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristic Group Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-group-temp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
