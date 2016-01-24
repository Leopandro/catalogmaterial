<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroup */

$this->title = 'Обновить характеристику группы: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-group-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
