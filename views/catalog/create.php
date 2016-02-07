<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.01.2016
 * Time: 19:00
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Создать раздел';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-form">
<!--    --><?// echo $message?>
    <? $form = ActiveForm::begin(); ?>

    <div class="hidden">
        <?
            $model->id = $id;
            echo $form->field($model, 'id');
        ?>
    </div>
        <?= $form->field($model, 'name') ?>
    <?= Html::submitButton('Добавить раздел', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
</div>
