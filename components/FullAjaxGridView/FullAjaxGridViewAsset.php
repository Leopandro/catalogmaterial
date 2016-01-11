<?php

namespace app\components\FullAjaxGridView;

use yii\web\AssetBundle;


class FullAjaxGridViewAsset extends AssetBundle { //extends \kartik\grid\GridViewAsset {

    public $sourcePath = '@app/components/FullAjaxGridView/assets';

    public $autoGenerate = true;

    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\web\JqueryAsset',
    ];
    public $js = [
        'js/fullajaxgridview.js',
    ];

} 