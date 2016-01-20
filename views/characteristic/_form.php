<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CharacteristicGroup;
/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroup */
/* @var $form yii\widgets\ActiveForm */
?>
<?

    if ($_GET['id_group'])
    {
        $model->id_group = $_GET['id_group'];
    }

?>
<div class="characteristic-group-form">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin(); ?>

        <div class="col-xs-2">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'type_value')->dropDownList(CharacteristicGroup::getDataForDropDownList()); ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'is_required')->dropDownList(CharacteristicGroup::getLabelForRequired()); ?>
        </div>

        <div class="hidden">
            <?= $form->field($model, 'id_group')->textInput() ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'is_visible')->dropDownList(CharacteristicGroup::getLabelForHidden());  ?>
        </div>

    </div>
    <div>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
