<?php

namespace app\components\FullAjaxGridView;

use Closure;
use yii\base\InvalidConfigException;
use yii\helpers\Html;


class FullAjaxCheckboxColumn extends \yii\grid\CheckboxColumn
{

    public $isOnlyOne = false;

    public function init()
    {
        parent::init();
    }


    protected function renderHeaderCellContent()
    {
        $name = rtrim($this->name, '[]') . '_all';
        $id = $this->grid->options['id'];
        $options = json_encode([
            'name' => $this->name,
            'multiple' => $this->multiple,
            'checkAll' => $name,
            'isOnlyOne'=>$this->isOnlyOne
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->grid->getView()->registerJs("jQuery('#$id').fullAjaxGridView('setSelectionColumn', $options);");

        if ($this->header !== null || !$this->multiple) {
            return \yii\grid\Column::renderHeaderCellContent();
        } else {
            return Html::checkBox($name, false, ['class' => 'select-on-check-all']);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->checkboxOptions instanceof Closure) {
            $options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
        } else {
            $options = $this->checkboxOptions;
            if (!isset($options['value'])) {
                $options['value'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $key;
            }
        }

        return Html::checkbox($this->name, !empty($options['checked']), $options);
    }
}
