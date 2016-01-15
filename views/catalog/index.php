<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
function showChilds($leaves, $depth)
{
    $go = false;
    foreach($leaves as $leave)
    {
        if (($leave->depth == $depth))
        {
            $go = true;
        }
    }
    if ($go == false)
        return;
            echo "\\\"nodes\\\":[";
            foreach ($leaves as $leave) {
                if (($leave->depth == $depth)) {
                    echo "{";
                    if ($leave->node_type <> 0)
                    {
                        echo "\\\"node_type\\\":\\\"$leave->node_type\\\",";
                    }
                    echo "\\\"text\\\":\\\"$leave->name\\\",";
                    echo "\\\"id\\\":\\\"$leave->id\\\",";
                    showChilds($leaves, $leave->id);
                    echo "},";
                }
            }
            echo ']';
}
function showTree($leaves)
{
    echo '"[';
    foreach($leaves as $leave)
    {
        if($leave->depth == 0)
        {
            echo "{";
            echo "\\\"text\\\":\\\"$leave->name\\\",";
            echo "\\\"id\\\":\\\"$leave->id\\\",";
            showChilds($leaves, $leave->id);
            echo "}";
        }
    }
    echo ']"';
}

$script = <<< JS
    var testree2 = [{text:"Countries",id:"1",nodes:[{text:"Russia",id:"2",nodes:[{text:"Moscow",id:"3",},{text:"Perm",id:"9",},]},]}];
    function getTree() {
        testree = $("#gettree").html().trim();
        var tree = '';
        for (var i = 0; i < testree.length; i++)
        {
            if (testree[i] == ',')
            {
                if ((testree[i + 1] == '}') || (testree[i + 1]) == ']')
                {
                }
                else
                tree += testree[i];
            }
            else
                tree += testree[i];
        }
        console.log(tree);
        tree = JSON.parse(tree);
        return tree;
    }
    $('#section').click(function(){
        var x = $('.node-selected');
        var id = $(x).attr('id');
        var node_type = $(x).attr('node_type');
        console.log('id:',id,' node_type:',node_type);
        if ((node_type == undefined) && (id))
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
        if ((id))
        {
            var url = 'index.php?r=catalog%2Fedit&id='+id;
            $(location).attr('href',url);
        }
    });
    $('#tree').treeview({data: getTree()});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<div class="col-xs-2"><h4>Каталог разделов</h4></div>
<div class="col-xs-6">
    <p>
        <button type="button" class="btn btn-primary" id="section">Создать раздел</button>
        <button type="button" class="btn btn-success" id="edit">Редактировать раздел/группу</button>
    </p>
</div>
<div class="col-xs-12" id="tree">

</div>
<div class="hidden" id="gettree">
    <?
        showTree($leaves);
    ?>
</div>