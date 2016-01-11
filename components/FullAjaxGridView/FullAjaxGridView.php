<?php

namespace app\components\FullAjaxGridView;

use app\components\FullAjaxGridView\FullAjaxGridViewAsset;
use Yii;
use Closure;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\BaseListView;
use yii\base\Model;

class FullAjaxGridView extends \yii\grid\GridView {

    public $isFilterPjax = true;

    public function init()
    {

      parent::init();

    }


    public function run(){
        $id = $this->options['id'];
        $arrOptions = $this->getClientOptions();
        $arrOptions['isFilterPjax'] = $this->isFilterPjax;
        $options = Json::htmlEncode($arrOptions);
        $view = $this->getView();
        FullAjaxGridViewAsset::register($view);
        $view->registerJs("jQuery('#$id').fullAjaxGridView($options);");
        BaseListView::run();
    }

}