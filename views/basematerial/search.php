<?php
use yii\widgets\ActiveForm;

$script = <<< JS
$('#clearfilter').click(function(){
    $('input:not(:hidden)').each(function(){
        $(this).val('')
    });
    $('select').val('');
    $('#search').trigger("click");
})
JS;
$this->registerJs($script);
?>
<?
$form = ActiveForm::begin();
if ($model->columnSettings)
foreach ($model->columnSettings as $currentColumnSetting)
{
    echo '<div>'.$currentColumnSetting['name'].'</div>';
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
                $currentColumnSetting['value'], ["class" => "form-control"]).'<br>';
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
            ['prompt' => '', "class" => "form-control", "style" => "width:15%"]
            );
        echo \yii\helpers\Html::textInput(
                'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][firstvalue]',
                $currentColumnSetting['firstvalue'],
                [
                    "class" => "form-control",
                    "style" => "width:35%"
                ]);
        //echo $currentColumnSetting['label']['firstvalue'];
        echo \yii\helpers\Html::dropDownList(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][secondcompare]',
            $currentColumnSetting['secondcompare'
            ],
            \app\models\DynamicMaterialFormSearch::$comparasion,
            ['prompt' => '', "class" => "form-control", "style" => "width:15%"]
        );
        echo \yii\helpers\Html::textInput(
                'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][secondvalue]',
                $currentColumnSetting['secondvalue'],
                [
                    "class" => "form-control",
                    "style" => "width:35%"
                ]).'<br>';
        echo \yii\helpers\Html::hiddenInput(
            'DynamicMaterialFormSearch[columnSettings]['.$currentColumnSetting['label'].'][type_value]',
            $currentColumnSetting['type_value']);
    }
}
else
    echo '<p>Нет данных группы</p>'
?>
<br>
<button type="submit" id="search" class="btn btn-primary">Применить фильтр</button>
<button type="button" id="clearfilter" class="btn btn-warning">Очистить фильтр</button>

<?
ActiveForm::end() ?>
