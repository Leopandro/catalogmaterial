<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BaseMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Материалы группы ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['/basematerial/model', 'id' => $_GET['id']]);
$urlUpdate = Url::to(['/basematerial/update']);
$script = <<< JS
var id;
$('.listitem').click(function(){
    id = $(this).attr('id');
    var x = $.ajax({
        'url' : '{$url}'+'&material_id='+$(this).attr('id')
    }).done(function()
    {
        $("#detailview").empty();
        $("#detailview").append(x.responseText)
        $("#material").removeClass("hidden");
    });
})
$("#material").click(function(){
    var url = '{$urlUpdate}'+'&id='+id;
    $(location).attr('href', url);
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="base-material2-index">

    <h3><?
        echo Html::encode($this->title);
        if ($_GET['groupname'])
            echo ($_GET['groupname']);
        ?>
    </h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
