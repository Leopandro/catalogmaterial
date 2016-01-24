<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BaseMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Материалы группы ';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$('button[id]').click(function(){
    var x = $.ajax({
        'url' : 'http://catalogmaterial/web/index.php?r=basematerial%2Fmodel&id='+{$_GET['id']}+'&material_id='+$(this).attr('id')
    }).done(function()
    {
        $("#detailview").empty();
        $("#detailview").append(x.responseText)
    });
    x.onreadystatechange = function()
    {
        console.log(x.responseText);
        $("#detailview").empty();
        $("#detailview").append(x.responseText)
    }
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
                return Html::tag('button', $model->name, ['type' => 'button', 'class' => 'btn btn-sm btn-default', 'id' => $model->id, 'style'=>'width:100%;text-align: left']);
            }
        ]); ?>
    </div>
    <div class="col-xs-6" id="detailview">
<!--        --><?//= DetailView::widget([
//            'model' => $model,
//            'attributes' => $columns
//        ]);
//        ?>
    </div>
</div>
