<?
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 23.01.2016
 * Time: 15:33
 */
use yii\widgets\DetailView;

?>


<?= DetailView::widget([
    'model' => $model,
    'attributes' => $columns,
    'template' => '<tr><td style="width:25%">{label}</td><td>{value}</td></tr>'
]);
?>