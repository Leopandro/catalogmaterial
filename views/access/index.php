<?php

use app\models\AccessGroupsFormModel;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Catalog;

$this->title = 'Права доступа';
$this->params['breadcrumbs'][] = $this->title;

$dataTreeView = \app\models\Catalog::getTreeDataForAccessGroups();
$dataTreeViewJson = yii\helpers\Json::encode($dataTreeView);

$script = <<< JS

    $('#treeAccessGroups').treeview({
        data: $dataTreeViewJson,
        showCheckbox:true,
        onNodeChecked : function(event, data) {

            console.log("node select");

            //пройти циклом по data.nodes который содержит дочерние элементы

            var dataAccessGroupsFromTree = $('#treeAccessGroups').treeview("getChecked");
            var idsChecked = [];
            $.each(dataAccessGroupsFromTree,function(index,item){
                idsChecked.push(item.id)
            });

            $("#accessgroupsformmodel-accessgroups").val(idsChecked.join(','));
        }
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>

<div class="row">
    <? $form = ActiveForm::begin(); ?>

    <div class="col-xs-6">

        <?php
            echo $form->field($modelForm, 'accessGroups')->hiddenInput()->label(false);
        ?>
        <?php
            echo $form->field($modelForm, 'user')->dropDownList($users);
        ?>
        <!--    --><?//= $form->field($modelForm, 'name') ?>


    </div>

    <div class="col-xs-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success','style'=>'margin-top: 38px;','id'=>'saveBtn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="row">

    <div class="col-xs-6">

        <div class="col-xs-12" id="treeAccessGroups" style="padding-left: 0px;">
        </div>

    </div>

</div>


