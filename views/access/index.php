<?php

use app\models\AccessGroupsFormModel;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Catalog;

$this->title = 'Права доступа';
$this->params['breadcrumbs'][] = $this->title;

$dataTreeView = \app\models\Catalog::getTreeDataForAccessGroupsAndSelected($modelForm->idsAllowAccessGroupsMaterial);
$dataTreeViewJson = yii\helpers\Json::encode($dataTreeView);

$addressAccessIndex = yii\helpers\Url::toRoute(['access/index']);

$script = <<< JS

$( document ).ready(function() {

    if(!$.catalogmaterial)
        $.catalogmaterial = {};

    $.catalogmaterial.eachRecursiveTree = function(nodes,applyFunct){
        if($.isArray(nodes)) {
            $.each(nodes,function(index,item){

                if($.isArray(item.nodes))
                    $.catalogmaterial.eachRecursiveTree(item.nodes,applyFunct);


                if($.isFunction(applyFunct))
                    applyFunct.call(null,item);

            });
        }
    };

    $('#treeAccessGroups').treeview({
        data: $dataTreeViewJson,
        showCheckbox:true,
        onNodeChecked : function(event, data) {

//            console.log("node select");
            //пройти циклом по data.nodes который содержит дочерние элементы
            $.catalogmaterial.eachRecursiveTree(data.nodes,function(node){
                $('#treeAccessGroups').treeview('checkNode', [ node.nodeId, { silent: true } ]);
            });

            var siblingsNode = $('#treeAccessGroups').treeview('getSiblings', [ data.nodeId ]);
            var isCheckParentNode = true;
            $.each(siblingsNode,function(index,item) {
                if(item.state.checked == false) {
                    isCheckParentNode = false;
                    return false;
                }

            });
            if(isCheckParentNode) {
                var parentNode = $('#treeAccessGroups').treeview('getParent', [ data.nodeId ]);
                if(parentNode && $.isPlainObject(parentNode))
                    $('#treeAccessGroups').treeview('checkNode', [ parentNode.nodeId, { silent: true } ]);
            }

            var dataAccessGroupsFromTree = $('#treeAccessGroups').treeview("getChecked");
            var idsChecked = [];
            $.each(dataAccessGroupsFromTree,function(index,item){ idsChecked.push(item.id) });

            $("#accessgroupsformmodel-accessgroups").val(idsChecked.join(','));
        }, onNodeUnchecked  : function(event, data) {

//            console.log("node unchecked");

            //пройти циклом по data.nodes который содержит дочерние элементы
            $.catalogmaterial.eachRecursiveTree(data.nodes,function(node){
//                console.log('node set unchecked silent '+node.id);
                $('#treeAccessGroups').treeview('uncheckNode', [ node.nodeId, { silent: true } ]);
            });



            //if all siblings unchecked then uncheck parent node
            var siblingsNode = $('#treeAccessGroups').treeview('getSiblings', [ data.nodeId ]);
            var isUncheckParentNode = true;
            $.each(siblingsNode,function(index,item) {
                if(item.state.checked == true) {
                    isUncheckParentNode = false;
                    return false;
                }

            });
            if(isUncheckParentNode) {
                var parentNode = $('#treeAccessGroups').treeview('getParent', [ data.nodeId ]);
                if(parentNode && $.isPlainObject(parentNode))
                    $('#treeAccessGroups').treeview('uncheckNode', [ parentNode.nodeId, { silent: true } ]);
            }


            var dataAccessGroupsFromTree = $('#treeAccessGroups').treeview("getChecked");
            var idsChecked = [];
            $.each(dataAccessGroupsFromTree,function(index,item){ idsChecked.push(item.id); });

            $("#accessgroupsformmodel-accessgroups").val(idsChecked.join(','));
        },
    });


    $(document).on('change','#accessgroupsformmodel-user',function(event){
        //console.log('change');
        window.location.href = "$addressAccessIndex"+"&idUser="+$('#accessgroupsformmodel-user').val();
    });

});


JS;
$this->registerJs($script, yii\web\View::POS_READY);

?>

<div class="row">

    <div class="col-lg-12">
        <?php if(Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
    </div>

    <? $form = ActiveForm::begin(); ?>

    <div class="col-xs-6">
        <?php
            echo $form->field($modelForm, 'accessGroups')->hiddenInput()->label(false);
        ?>
        <?php
            echo $form->field($modelForm, 'user')->dropDownList($users,['prompt'=>'-- Пользователь --']);
        ?>
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


