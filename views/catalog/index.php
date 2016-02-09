<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */

$this->title = 'Каталог разделов';
$this->params['breadcrumbs'][] = $this->title;

//ссылка (переход при дабл клике на страницу группы)
$urlIndex = Url::toRoute(['/basematerial/index']);

//Действия с excel
$urlUpload = Url::toRoute(['/basematerial/import']);
$url = Url::toRoute(['/basematerial/excel']);

//Действия с группами
$urlCreate = Url::toRoute(['/catalog/create']);
$urlEdit = Url::toRoute(['/catalog/edit']);
$urlDelete = Url::toRoute(['/catalog/deletesection']);

//Действия с разделами
$urlGroup = Url::toRoute(['/catalog/group']);
$urlEditGroup = Url::toRoute(['/catalog/editgroup']);
$urlDeleteGroup = Url::toRoute(['/catalog/deletegroup']);

$script = <<< JS
    //Куки имени раздела если вдруг перешел из страницы создания/редактирования раздела
    setCookie('group-name', '');

    //Действия с группами
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
    $('#delete').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 0 || node_type == undefined))
        {
            var url = '{$urlDelete}'+'?id='+id;
            $(location).attr('href',url);
        }
        else
        {
            alert('Выберите раздел для редактирования');
        }
    });

    //Действия с разделами
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
    $('#deletegroup').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((id) && (node_type == 1))
        {
            var url = '{$urlDeleteGroup}'+'?id='+id;
            $(location).attr('href', url);
        }
        else
        {
            alert('Выберите группу для редактирования');
        }
    });

    //Действия с excel
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

    //Отображение дерева
    $('#tree').treeview({data: $leaves,renderCustom:function(options,treeItem,node){
         if($(treeItem).hasClass('group-color')){
            $(treeItem).attr('title','Нажмите 2 раза для просмотра материала.')
        }

    }});

    //Переход при дабл клике на страницу группы
    $('#tree').on('dblclick','.group-color',function(event){
        var id = $(this).attr('id');
        var url = '{$urlIndex}'+'?id='+id;
        $(location).attr('href', url);
    });

JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="col-xs-2"><h4>Каталог разделов</h4></div>
<div class="col-xs-10">
    <nav class="navbar">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Разделы
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="section">Создать раздел</a></li>
                        <li><a href="#" id="edit">Редактировать раздел</a></li>
                        <li><a href="#" id="delete">Удалить раздел</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Группы
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="group">Создать группу</a></li>
                        <li><a href="#" id="editgroup">Редактировать группу</a></li>
                        <li><a href="#" id="deletegroup">Удалить группу</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Excel
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" id="outputtoxml">Экспорт в Excel</a></li>
                        <li><a href="#" id="inputfromxml">Импорт из Excel</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="col-xs-12" id="tree">
</div>