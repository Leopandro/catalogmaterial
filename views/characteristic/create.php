<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CharacteristicGroup */

$this->title = 'Создать характеристику группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-group-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
