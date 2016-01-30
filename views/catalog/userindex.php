<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */

$this->title = 'Каталог разделов';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::toRoute(['/basematerial/index']);
$script = <<< JS
console.log({$leaves});
$('#tree').treeview({data: $leaves});
    setCookie('group-name', '');
$('#tree').on('dblclick','.group-color',function(event){
    var id = $(this).attr('id');
    var url = '{$url}'+'&id='+id;
    $(location).attr('href', url);
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="col-xs-2"><h4>Каталог разделов</h4></div>
<div class="col-xs-12" id="tree">
</div>
