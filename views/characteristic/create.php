<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroup */

$this->title = 'Create Characteristic Group';
$this->params['breadcrumbs'][] = ['label' => 'Characteristic Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
