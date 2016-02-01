<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use \yii\bootstrap\Modal;

$url = Url::toRoute(['/basematerial/model', 'id' => $group_id]);
$urlReport = Url::toRoute(['basematerial/addreport']);
$urlGetExcel = Url::toRoute(['basematerial/report']);
$script = <<< JS
var idMaterial;
$('.listitem').click(function(){
    idMaterial = $(this).attr('id');
    var x = $.ajax({
        'url' : '{$url}'+'&material_id='+idMaterial
    }).done(function()
    {
        $("#detailview").empty();
        $("#detailview").append(x.responseText);
        $("#buttonlist").removeClass("hidden");
        $("#resultmessage").addClass("hidden");
    });
})
$("#test").click(function(){
    var x = $.ajax({
        'url' : '{$urlGetExcel}'
    }).done(function(data){
        console.log(x);
        console.log(data);
    })
})
$("#report").click(function(){
    var x = $.ajax({
        'url' : '{$urlReport}'+'&id='+idMaterial+'&group_id='+'{$group_id}'
    }).done(function()
    {
        console.log(x);
        $("#resultmessage").empty();
        $("#resultmessage").append(x.responseText);
        $("#resultmessage").removeClass("hidden");;
    });
})
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<? if (!$message) {?>
<div class="base-material2-index">

    <h3><?
        echo Html::encode($this->title);
        if ($group_name)
            echo $group_name;
        ?>
    </h3>
    <div>
        <?= Html::a('Назад в каталог', ['/catalog/index'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Сгенерировать отчет', ['/basematerial/report'], ['class' => 'btn btn-primary']) ?>

        <?
        Modal::begin([
            'header' => '<h4> Поиск по фильтру </h4>',
            'toggleButton' => ['label' => 'Фильтр','class' => 'btn btn-primary']
        ]);
        echo
        $this->render('search',
            [
                'model' => $model
            ]);
        Modal::end();?>
    </div>
    <div class="col-xs-3">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model) {
                return Html::tag('button', $model->name, ['type' => 'button', 'class' => 'btn btn-sm btn-default listitem', 'id' => $model->id, 'style'=>'width:200px;text-align: left']);
            }
        ]); ?>
    </div>
    <div class="col-xs-6">
        <div id="detailview"></div>
        <div id="buttonlist" class="hidden">
            <button class="btn btn-success" id="report">В отчет</button>
        </div>
        <div id="resultmessage">

        </div>
    </div>
</div>
<? }
else
{
    ?>
    <p>
        <?= Html::a('Назад в каталог', ['/catalog/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?
        echo '<h4>'.$message.'</h4>';
};
    ?>