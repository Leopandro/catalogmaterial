<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\base\DynamicModel;

/* @var $this yii\web\View */
/* @var $model app\models\BaseMaterial2 */

$this->title = 'Создание материала';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="base-material2-update">
    <h3><?= Html::encode($this->title).": ".$modelForm->name; ?></h3>

    <?php $form = ActiveForm::begin(); ?>

    <?php

    foreach ($modelForm->attributes() as $curNameAttribute) {

        if(isset($modelForm->$curNameAttribute)) {

            $valueAttribute = $modelForm->$curNameAttribute;
            $dataModelOfAttribute = $modelForm->getDataModelByNameAttribute($curNameAttribute);

            switch($dataModelOfAttribute['type_widget']) {
                case 'hidden':

                    echo $form->field($modelForm, $curNameAttribute)->hiddenInput()->label(false);

                    break;

                case 'textfield':

                    echo $form->field($modelForm, $curNameAttribute)->textInput();

                    break;

                case 'datepicker':

                    echo  $form->field($modelForm, $curNameAttribute)->widget(DatePicker::classname(), [
                        //'language' => 'ru',
                        'dateFormat' => 'php:d.m.Y',
                        'options'=>['class'=>'form-control']
                    ]);

                    //$form->field($modelForm, $curNameAttribute)->textInput()->label($dataModelOfAttribute['label']);

                    break;

            }

        }


    }

    ?>


    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
