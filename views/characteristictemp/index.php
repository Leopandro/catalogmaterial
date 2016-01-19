<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CharacteristicGroupTempSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Characteristic Group Temps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-group-temp-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Characteristic Group Temp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'label',
            'name_table',
            'type_value',
            // 'is_required',
            // 'is_visible',
            // 'id_user',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
