<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.01.2016
 * Time: 19:00
 */
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Создать группу';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-form">
<!--    --><?// echo $message?>
    <? $form = ActiveForm::begin(); ?>

    <div class="hidden">
        <?=$form->field($model, 'id') ?>
    </div>
    <?= $form->field($model, 'name') ?>
</div>
    <div class="characteristic-group-index">
        <h5><b><? echo 'Характеристики группы:' ?></b></h5>

        <p>
            <?= Html::a('Добавить характеристику', ['characteristic/create', 'id_group' => $_GET['id']], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $attributesModel,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                [
                    'attribute' => 'label',
                    'header' => 'Описание'
                ],
                [
                    'attribute' => 'type_value',
                    'header' => 'Тип значения',
                    'value' => function($model)
                    {
                        return CharacteristicGroup::getLabelTypeById($model->type_value);
                    }
                ],
                [
                    'attribute' => 'is_required',
                    'header' => 'Обязательность',
                    'value' => function($model)
                    {
                        return CharacteristicGroup::getLabelForRequiredById($model->is_required);
                    }
                ],
                [
                    'attribute' => 'is_visible',
                    'header' => 'Видимость',
                    'value' => function($model)
                    {
                        return CharacteristicGroup::getLabelForHiddenById($model->is_visible);
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' =>
                        [
                            'update' => function ($url, $model)
                            {
                                return Html::a(
                                    'Редактировать',
                                    ['characteristic/update', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-success'
                                    ]
                                );
                            },
                            'delete' => function ($url, $model)
                            {
                                return Html::a(
                                    'Удалить',
                                    ['characteristic/delete', 'id' => $model->id],
                                    [
                                        'data-confirm' => 'Удалить?',
                                        'class' => 'btn btn-danger'
                                    ]
                                );
                            }
                        ],
                    'template' => '{update} {delete}'
                ],
            ],
        ]); ?>
    </div>
<?= Html::submitButton('Добавить группу', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>