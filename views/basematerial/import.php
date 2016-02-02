<?php
use yii\widgets\ActiveForm;

$this->title = 'Импорт данных';
$this->params['breadcrumbs'][] = $this->title;
$script = <<< JS
function isDublicate(arr)
{
    for (var i = 0; i < arr.length; i++)
    {
        for (var j = 0; j < arr.length; j++)
        {
            if ((arr[i] == arr[j]) && (i != j) && (arr[i] != '') && (arr[j] != ''))
               return true;
        }
    }
    return false;
}
function isInArray(arr, el)
{
    for (var i=0; i < arr.length; i++)
    {
        if (arr[i] == el)
        return true;
    }
    return false;
}
$('form button[type=submit]').click(function(){
    var arrColumns = [];
    var i = 0;
    $('select').each(function()
    {
        arrColumns[i] = $(this).val();
        i++;
    });
    $('.alert-danger').addClass('hidden');
    if (arrColumns[0] == '')
    {
        $('.alert-danger').removeClass('hidden');
        $('.alert-danger').text('Выберите колонку соответствующую наименованию');
        return false;
    }
    if (isDublicate(arrColumns))
    {
        $('.alert-danger').removeClass('hidden');
        $('.alert-danger').text('Колонки не могут повторяться');
        return false;
    }
    return true;
});

JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerCss("
.columnName {

}
");
?>

<h3><? echo 'Импорт для группы '.$catalog->name?></h3>
<div class="row">
    <?
    $formList = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>
    <div class="col-xs-12">
    <? echo $formList->field($importModel, 'file')->fileInput();?>
    </div>
    <div class="col-xs-8">
        <table class="table table-bordered table-striped">
            <tr><td><b>Характеристика</b></td><td><b>Соотвествующее имя столбца в Excel</b></td></tr>
        <?
        foreach ($importModel->columnSettings as $currentColumnSetting)
        {
            ?>
                <tr>
                    <td>
                        <?
                            echo $currentColumnSetting['label'];
                            echo \yii\helpers\Html::hiddenInput('ImportModel[columnSettings]['.$currentColumnSetting['name'].'][name]', $currentColumnSetting['name']);
                            echo \yii\helpers\Html::hiddenInput('ImportModel[columnSettings]['.$currentColumnSetting['name'].'][label]', $currentColumnSetting['label']);
                        ?>
                    </td>
                    <td>
                         <?
                            echo \yii\helpers\Html::dropDownList('ImportModel[columnSettings]['.$currentColumnSetting['name'].'][nameExcelColumn]',
                            $currentColumnSetting['nameExcelColumn'], \app\models\ImportModel::$columnNamesExcel,
                                    ['prompt' => 'Выберите колонку', 'class'=>'form-control']);
                         ?>
                    </td>
                </tr>
            <?
        }
        ?>
        </table>
<!--        --><?// echo $formList->field($importModel, 'startRow') ?>
        <div class="alert alert-danger hidden">

        </div>
        <button type="submit" class="btn btn-primary">Импортировать</button>
    </div>
    <? ActiveForm::end() ?>
</div>
