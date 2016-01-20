<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="characteristic-group-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_table') ?>

    <?= $form->field($model, 'type_value') ?>

    <?php // echo $form->field($model, 'is_required') ?>

    <?php // echo $form->field($model, 'id_group') ?>

    <?php // echo $form->field($model, 'is_visible') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
