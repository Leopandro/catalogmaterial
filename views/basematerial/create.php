<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BaseMaterial2 */

$this->title = 'Create Base Material2';
$this->params['breadcrumbs'][] = ['label' => 'Base Material2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-material2-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
