<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BaseMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Base Material2s';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$('button[id]').click(function(){
    var x = $.ajax({
        'url' : 'http://catalogmaterial/web/index.php?r=basematerial%2Fmodel&id='+{$_GET['id']}+'&material_id='+$(this).attr('id')
    });
    console.log(x);
})
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="base-material2-index">

    <h3><?
        echo Html::encode($this->title);
        if ($_GET['id']) echo ', id каталога = '.$_GET['id']
        ?>
    </h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Base Material2', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="col-xs-6">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model) {
                return Html::tag('button', $model->name, ['type' => 'button', 'class' => 'btn btn-sm btn-default', 'id' => $model->id]);
            }
        ]); ?>
    </div>
    <div class="col-xs-6">
        <?php Pjax::begin(); ?>
        <?= Html::a("Refresh", ['basematerial/index'], ['class' => 'btn btn-lg btn-primary']) ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $columns
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
