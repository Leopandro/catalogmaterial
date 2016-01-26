<?php

namespace app\models;

use yii\base\DynamicModel;


class DynamicFormMaterial extends  DynamicModel {


    //хранятся данные об атрибутах, в том числе о правилах валидации, типе виджета для отображения
    protected $_dataModel;

    public function __construct($idGroupMaterial) {

        $this->_dataModel = $dataAttributes = BaseMaterial::getAttributesByGroupId($idGroupMaterial);

        $nameAttributes = [];

        foreach ($dataAttributes as $curAttr)
            $nameAttributes[] = $curAttr['attribute'];

        parent::__construct($nameAttributes);
    }

    public function loadDataMaterial() {

        if($this->id && $this->group_id) {

            $groupData = Catalog::findOne(['id'=>intval($this->group_id)]);
            $model = BaseMaterial::getModel($groupData, intval($this->id));
            $currentAttributes = $this->attributes();
            foreach ($currentAttributes as $curAttrName) {
                $valueCurAttr = isset($model->$curAttrName) ? $model->$curAttrName : null ;
                if($curAttrName != 'group_id')
                    $this->$curAttrName = $valueCurAttr;
            }

        }

    }

    public function getDataModelByNameAttribute($nameAttr) {

        $result = null;

        foreach ($this->_dataModel as $curDataModel) {
            if($nameAttr == $curDataModel['attribute']) {
                $result = $curDataModel;
                break;
            }
        }

        return $result;
    }

    //-----------------------------------------------------------------------

    public function rules()
    {

        $result = [];

        foreach ($this->_dataModel as $curDataModel) {
            if(is_array($curDataModel['rules_validate']) && count($curDataModel['rules_validate'])>0)
                $result = array_merge($result,$curDataModel['rules_validate']);
        }

        return $result;
    }


    public function attributeLabels() {

        $result = [];

        foreach ($this->_dataModel as $curDataModel) {
                $result[$curDataModel['attribute']] = $curDataModel['label'];
        }

        return $result;

    }

} 