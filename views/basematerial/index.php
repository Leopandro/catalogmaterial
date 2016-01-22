<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BaseMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Base Material2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-material2-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Base Material2', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<div class="col-xs-6">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'date_verify',
            'source',
            'code',
            // 'note',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
