<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 31.01.2016
 * Time: 19:53
 */

namespace app\models;

use Yii;
use yii\base\Model;

class DynamicMaterialFormSearch extends Model
{
    public $columnSettings;
    public static $comparasion = [
        '>' => '>',
        '<' => '<',
        '>=' => '>=',
        '<=' => '<=',
        '=' => '='
    ];
    public function getSearchForm($id)
    {
        $settings = CharacteristicGroup::find()->where(['id_group' => $id])->all();
        foreach ($settings as $setting)
        {
            //Если это строка
            if ($setting['type_value'] == 1)
                $this->columnSettings[$setting['label']] = [
                    'name' => $setting['name'],
                    'label' => $setting['label'],
                    'value' => '',
                    'type_value' => $setting['type_value']
                ];
            else
            {
                $this->columnSettings[$setting['label']] = [
                    'name' => $setting['name'],
                    'label' => $setting['label'],
                    'firstcompare' => '',
                    'firstvalue' => '',
                    'secondcompare' => '',
                    'secondvalue' => '',
                    'type_value' => $setting['type_value']
                ];
            }
        }
    }
    public function rules()
    {
        return [
            ['columnSettings', 'safe']
        ];
    }
}