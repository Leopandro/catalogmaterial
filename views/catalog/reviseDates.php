<?php

use yii\grid as yiiGrid;
use yii\jui\DatePicker;

$this->title = 'Сверка дат';
$this->params['breadcrumbs'][] = $this->title;
//yiiGrid\DataColumn::className();


$script = <<< JS
$(document).on('click', '.refresh-revise-data-material', function(e) {

    e.preventDefault();
    var address = $(e.target).closest('a').attr('href');

    console.log('click refresh');
    $.ajax({
       url: address,
       data: {id: $(e.target).closest('a').attr('data-id')},
       success: function(rawData) {
            var data = JSON.parse(rawData);
           //console.log('success refresh date verify '+data.message);
           if(data.success) {
                $(e.target).closest('tr').find('.date-verify-column').text(data.date);
           }

       }
    });

    return false;
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>


<?php \yii\widgets\Pjax::begin(); ?>

<?= yiiGrid\GridView::widget([
    'id'=>'base-material-grid-view',
    'dataProvider' 	=> $model->search(),
    'filterModel'  	=> $model,
    //'layout'  		=> "{pager}\n{items}\n{pager}",
    'columns' => [
        [
            'label'=>'Наименование',
            'attribute'=>'name',
            'filter'=>false
        ],

        [
            'label'=>'Дата сверки',
            'format' => 'date',
            'attribute'=>'date_verify',
            'filter' => DatePicker::widget(
                    [
                        'model' => $model,
                        'attribute' => 'date_verify',
                        'options' => [
                            'class' => 'form-control'
                        ],
                        'clientOptions' => [
                            'dateFormat' => 'dd.mm.yy',
                        ]
                    ]
                ),
            'contentOptions' => ['style' => 'width:160px;','class'=>'date-verify-column'],
            'headerOptions' => ['style' => 'width:160px;'],
        ],

        [
            'label'=>'Источник',
            'attribute'=>'source',
            'filter'=>false
        ],

        [
            'label'=>'Шифр',
            'attribute'=>'code',
            'filter'=>false
        ],

        [
            'label'=>'Комментарий',
            'attribute'=>'note',
            'filter'=>false
        ],

        [
            'class' => \yii\grid\ActionColumn::className(),
            'buttons'=>[
                'view'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['/catalog/refresh-revise-data-of-material','id'=>$model->id]); //$model->id для AR
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-refresh"></span>', $customurl,
                            ['title' => 'Обновить дату сверки', 'data-pjax' => '0','class'=>'refresh-revise-data-material','data-id'=>$model->id]);
                    }
            ],
            'template'=>'{view}',
        ]

        /*[
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                } else {
                    return date('Y-m-d G:i:s', $model->created_at);
                }
            },
            'filter' => DatePicker::widget([
                'model'      => $searchModel,
                'attribute'  => 'created_at',
                'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control',
                ],
            ]),
        ],*/
//        [
//            'header' => Yii::t('user', 'Confirmation'),
//            'value' => function ($model) {
//                    if ($model->isConfirmed) {
//                        return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
//                    } else {
//                        return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
//                            'class' => 'btn btn-xs btn-success btn-block',
//                            'data-method' => 'post',
//                            'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
//                        ]);
//                    }
//                },
//            'format' => 'raw',
//            'visible' => Yii::$app->getModule('user')->enableConfirmation,
//        ],
//        [
//            'header' => Yii::t('user', 'Block status'),
//            'value' => function ($model) {
//                    if ($model->isBlocked) {
//                        return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
//                            'class' => 'btn btn-xs btn-success btn-block',
//                            'data-method' => 'post',
//                            'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
//                        ]);
//                    } else {
//                        return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
//                            'class' => 'btn btn-xs btn-danger btn-block',
//                            'data-method' => 'post',
//                            'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
//                        ]);
//                    }
//                },
//            'format' => 'raw',
//        ],
//        [
//            'class' => 'yii\grid\ActionColumn',
//            'template' => '{update} {delete}',
//        ],
    ],
]); ?>

<?php \yii\widgets\Pjax::end() ?>