<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CharacteristicGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Characteristic Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Characteristic Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'label',
            'type_value',
            // 'is_required',
            // 'id_group',
            // 'is_visible',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
