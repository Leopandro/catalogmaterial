<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Section */
/* @var $form ActiveForm */
?>
<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="hidden"><? echo$form->field($model, 'id') ?></div>
        <?= $form->field($model, 'name') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Добавить раздел', ['class' => 'btn btn-success', 'id' => 'new_section']) ?>
            <?= Html::submitButton('Добавить группу', ['class' => 'btn btn-primary', 'id' => 'new_group']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- menu-_form -->
