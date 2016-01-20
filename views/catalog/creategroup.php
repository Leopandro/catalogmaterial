<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.01.2016
 * Time: 19:00
 */
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\CharacteristicGroup;
use yii\widgets\ActiveForm;
$this->title = 'Создать группу';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$('.btn').click(function()
{
    setCookie('group-name', $('#groupsectionform-name').val(), 999);
});
$('#groupsectionform-name').val(getCookie('group-name'));
JS;


$this->registerJs($script, yii\web\View::POS_READY);
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

        <p>
            <?= Html::a(
                'Добавить характеристику',
                [
                    '/characteristictemp/create',
                    'id_user' => Yii::$app->user->identity->id,
                    'referer' => \yii\helpers\Url::current()
                ],
                [
                    'class' => 'btn btn-success',
                ]
                ) ?>
        </p>
        <h5><b><? echo 'Характеристики группы:' ?></b></h5>

        <?= GridView::widget([
            'dataProvider' => $attributesModel,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
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
                                    ['characteristictemp/update', 'id' => $model->id, 'referer' => \yii\helpers\Url::current()],
                                    [
                                        'class' => 'btn btn-success'
                                    ]
                                );
                            },
                            'delete' => function ($url, $model)
                            {
                                return Html::a(
                                    'Удалить',
                                    ['characteristictemp/delete', 'id' => $model->id, 'referer' => \yii\helpers\Url::current()],
                                    [
                                        'data-confirm' => 'Удалить?',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post'
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