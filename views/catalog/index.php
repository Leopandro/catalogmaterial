<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Каталог разделов';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['/basematerial/excel']);
$script = <<< JS
    $('#section').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((node_type == 0 || node_type == undefined) && (id))
        {
            var url = 'index.php?r=catalog%2Fcreate&id='+id;
            $(location).attr('href',url);
        }
    });
    $('#edit').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 0 || node_type == undefined))
        {
            var url = 'index.php?r=catalog%2Fedit&id='+id;
            $(location).attr('href',url);
        }
    });
    $('#group').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 0 || node_type == undefined))
        {
            var url = 'index.php?r=catalog%2Fgroup&id='+id;
            $(location).attr('href',url);
        }
    });
    $('#editgroup').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 1))
        {
            var url = 'index.php?r=catalog%2Feditgroup&id='+id;
            $(location).attr('href', url);
        }
    });

    $('#tree').treeview({data: $leaves});
    setCookie('group-name', '');


    $('#tree').on('dblclick','.group-color',function(event){
        var id = $(this).attr('id');
        var groupname = $(this).text();
        var url = 'index.php?r=basematerial%2Findex&id='+id+'&groupname='+groupname;
        $(location).attr('href', url);
    });

    $("#outputtoxml").click(function(){
        var node = $(".node-selected");
        var id = node.attr("id");
        var node_type = node.attr("node_type");
        console.log(id,node_type);
        if ((id) && (node_type == 1))
        {
            var url = '{$url}'+'&id='+id;
            $(location).attr('href', url);
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
        <button type="button" class="btn btn-info" id="outputtoxml">Вывести в XML</button>
    </p>
</div>
<div class="col-xs-12" id="tree">
</div>