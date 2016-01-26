<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\models\DynamicFormMaterial;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BaseMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Материалы группы ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['/basematerial/model', 'id' => $group_id]);
$urlUpdate = Url::to(['/basematerial/update']);
$script = <<< JS
var idMaterial;
$('.listitem').click(function(){
    idMaterial = $(this).attr('id');
    var x = $.ajax({
        'url' : '{$url}'+'&material_id='+idMaterial
    }).done(function()
    {
        console.log(x);
        $("#detailview").empty();
        $("#detailview").append(x.responseText)
        $("#material").removeClass("hidden");
    });
})
$("#material").click(function(){
    var url = '{$urlUpdate}'+'&id='+idMaterial+'&group_id='+'{$group_id}';
    console.log('material click');
    $(location).attr('href', url);
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
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
        <button class="btn btn-success hidden" id="material">Редактировать</button>
    </div>
</div>
