<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$url = Url::toRoute(['/basematerial/model', 'id' => $group_id]);
$urlReport = Url::toRoute(['basematerial/addreport']);
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
        $("#report").removeClass("hidden");
        $("#resultmessage").addClass("hidden");
    });
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
    $(location).attr('href', url);
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
    </div>
    <div class="col-xs-3">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model) {
                return Html::tag('button', $model->name, ['type' => 'button', 'class' => 'btn btn-sm btn-default listitem', 'id' => $model->id, 'style'=>'width:100%;text-align: left']);
            }
        ]); ?>
    </div>
    <div class="col-xs-6">
        <div id="detailview"></div>
        <button class="btn btn-success hidden" id="report">В отчет</button>
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