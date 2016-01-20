<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Каталог разделов';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
    $('#section').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((node_type == 0) && (id))
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
        if ((id) && (node_type == 0))
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
        if ((id) && (node_type == 0))
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
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="col-xs-2"><h4>Каталог разделов</h4></div>
<div class="col-xs-8">
    <p>
        <button type="button" class="btn btn-primary" id="section">Создать раздел</button>
        <button type="button" class="btn btn-success" id="edit">Редактировать раздел</button>
        <button type="button" class="btn btn-primary" id="group">Создать группу</button>
        <button type="button" class="btn btn-success" id="editgroup">Редактировать группу</button>
    </p>
</div>
<div class="col-xs-12" id="tree">
</div>