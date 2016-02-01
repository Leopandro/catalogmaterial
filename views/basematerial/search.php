<?php
use yii\widgets\ActiveForm;

?>
<?
$form = ActiveForm::begin();

foreach ($model->columnSettings as $currentColumnSetting)
{
    echo $currentColumnSetting['name'];
    if ($currentColumnSetting['type_value'] == 1)
    {
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][name]',
            $currentColumnSetting['name']);
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][label]',
            $currentColumnSetting['label']);
        echo \yii\helpers\Html::textInput(
                'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][value]',
                $currentColumnSetting['value']).'<br>';
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][type_value]',
            $currentColumnSetting['type_value']);
    }
    else
    {
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][name]',
            $currentColumnSetting['name']);
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][label]',
            $currentColumnSetting['label']);
        echo \yii\helpers\Html::dropDownList(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][firstcompare]',
            $currentColumnSetting['firstcompare'],
            \app\models\DynamicMaterialFormSearch::$comparasion,
            ['prompt' => '']
            );
        echo \yii\helpers\Html::textInput(
                'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][firstvalue]', $currentColumnSetting['firstvalue']);
        //echo $currentColumnSetting['label']['firstvalue'];
        echo \yii\helpers\Html::dropDownList(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][secondcompare]',
            $currentColumnSetting['secondcompare'],
            \app\models\DynamicMaterialFormSearch::$comparasion,
            ['prompt' => '']
        );
        echo \yii\helpers\Html::textInput(
                'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][secondvalue]', $currentColumnSetting['secondvalue']).'<br>';
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][type_value]',
            $currentColumnSetting['type_value']);
    }
}
?>
<button type="submit" class="btn btn-primary">Поиск по фильтру</button>
<?
ActiveForm::end() ?>
