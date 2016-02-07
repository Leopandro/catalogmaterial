<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */

$this->title = 'Каталог разделов';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::toRoute(['/basematerial/excel']);
$urlIndex = Url::toRoute(['/basematerial/index']);
$urlUpload = Url::toRoute(['/basematerial/import']);
$urlCreate = Url::toRoute(['/catalog/create']);
$urlEdit = Url::toRoute(['/catalog/edit']);
$urlGroup = Url::toRoute(['/catalog/group']);
$urlEditGroup = Url::toRoute(['/catalog/editgroup']);
$script = <<< JS
    $('#section').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((node_type == 0 || node_type == undefined) && (id))
        {
            var url = '{$urlCreate}'+'?id='+id;
            $(location).attr('href',url);
        }
        else
        {
            alert('Выберите раздел для добавления подраздела');
        }
    });
    $('#edit').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 0 || node_type == undefined))
        {
            var url = '{$urlEdit}'+'?id='+id;
            $(location).attr('href',url);
        }
        else
        {
            alert('Выберите раздел для редактирования');
        }
    });
    $('#group').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 0 || node_type == undefined))
        {
            var url = '{$urlGroup}'+'?id='+id;
            $(location).attr('href',url);
        }
        else
        {
            alert('Выберите раздел для добавления группы');
        }
    });
    $('#editgroup').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 1))
        {
            var url = '{$urlEditGroup}'+'?id='+id;
            $(location).attr('href', url);
        }
        else
        {
            alert('Выберите группу для редактирования');
        }
    });

    $('#tree').treeview({data: $leaves,renderCustom:function(options,treeItem,node){
         if($(treeItem).hasClass('group-color')){
            $(treeItem).attr('title','Нажмите 2 раза для просмотра материала.')
        }

    }});
    setCookie('group-name', '');


    $('#tree').on('dblclick','.group-color',function(event){
        var id = $(this).attr('id');
        var url = '{$urlIndex}'+'?id='+id;
        $(location).attr('href', url);
    });

    $("#outputtoxml").click(function(){
        var node = $(".node-selected");
        var id = node.attr("id");
        var node_type = node.attr("node_type");
        console.log(id,node_type);
        if ((id) && (node_type == 1))
        {
            var url = '{$url}'+'?id='+id;
            $(location).attr('href', url);
        }
        else
        {
            alert('Выберите группу');
        }
    });
    $("#inputfromxml").click(function(){
        var node = $(".node-selected");
        var id = node.attr("id");
        var node_type = node.attr("node_type");
        if ((id) && (node_type == 1))
        {
            var url = '{$urlUpload}'+'?id='+id;
            $(location).attr('href', url);
        }
        else
        {
            alert('Выберите группу');
        }
    });

JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="col-xs-2"><h4>Каталог разделов</h4></div>
<div class="col-xs-10">
    <p>
        <button type="button" class="btn btn-primary" id="section">Создать раздел</button>
        <button type="button" class="btn btn-success" id="edit">Редактировать раздел</button>
        <button type="button" class="btn btn-primary" id="group">Создать группу</button>
        <button type="button" class="btn btn-success" id="editgroup">Редактировать группу</button>
        <button type="button" class="btn btn-info" id="outputtoxml">Экспорт в Excel</button>
        <button type="button" class="btn btn-info" id="inputfromxml">Импорт из Excel</button>
    </p>
</div>
<div class="col-xs-12" id="tree">
</div>