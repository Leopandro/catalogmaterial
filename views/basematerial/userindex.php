<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$url = Url::to(['/basematerial/model', 'id' => $group_id]);
$script = <<< JS
var idMaterial;
$('.listitem').click(function(){
    idMaterial = $(this).attr('id');
    var x = $.ajax({
        'url' : '{$url}'+'&material_id='+idMaterial
    }).done(function()
    {
        $("#detailview").empty();
        $("#detailview").append(x.responseText)
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
    <p>
        <?= Html::a('Назад в каталог', ['/catalog/index'], ['class' => 'btn btn-primary']) ?>
    </p>
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