<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>

    <button type="submit">Submit</button>

<?php ActiveForm::end() ?>

<div class = "col-xs-6">
<?
if ($cells)
{
    foreach ($cells as $cell)
    {
        echo $cell.'<br>';
    }
}
?>
</div>
<div class = "col-xs-6">
<?
if ($attributes)
{
    for ($i = 0; $i < count($attributes[0]); $i++)
    {
        echo $attributes[0][$i].'<br>';
    }
}
?>
</div>
