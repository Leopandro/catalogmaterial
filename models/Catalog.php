<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 12.01.2016
 * Time: 15:17
 */
namespace app\models;
use yii\base\Object;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;

class Catalog extends ActiveRecord
{

    const TYPE_GROUP = 1;
    const TYPE_PART = 0;

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CatalogQuery(get_called_class());
    }

    public  static function showChilds($leaves, $depth,$idsChecked=[])
    {
        $result = [];

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

        foreach ($leaves as $leave) {
            if (($leave->depth == $depth)) {
                $dataNode = [];
                if ($leave->node_type <> 0)
                {
                    $dataNode['node_type']=$leave->node_type;
                    $dataNode['icon']='glyphicon glyphicon-book';
                }
                else
                {
                    $dataNode['icon']='glyphicon glyphicon-folder-open';
                    $dataNode['state']['expanded'] = true;
                }

                if(in_array($leave->id,$idsChecked))
                    $dataNode['state']['checked'] = true;

                $dataNode['text']=$leave->name;
                $dataNode['id']=$leave->id;

                $dataNode['nodes'] = self::showChilds($leaves, $leave->id,$idsChecked);

                $result[] = (object)$dataNode;
            }
        }

        return $result;
    }
    public  static function  showTree($leaves,$idsChecked=[])
    {
        $result = [];
        foreach($leaves as $leave)
        {
            if($leave->depth == 0)
                $result[] = (object)['id'=>$leave->id,'text'=>$leave->name,'nodes'=>self::showChilds($leaves, $leave->id,$idsChecked)];
        }
        return $result;
    }

    public static function ShowChildsFix($root)
    {
        $childs = $root->children(1)->all();
        $arr = [];
        $childs = self::sortTree($childs);
        foreach ($childs as $child)
        {
            $obj = [];
            $state = [
                'expanded' => true
            ];
            $state = (object) $state;
            $obj['text'] = $child->name;
            $obj['state'] = $state;
            $obj['id'] = $child->id;
            if ($child->node_type == 0)
            {
                $obj['node_type'] = 0;
                $obj['icon'] = 'glyphicon glyphicon-folder-open';
            }
            else if ($child->node_type == 1)
            {
                $obj['node_type'] = 1;
                $obj['icon'] = 'glyphicon glyphicon-book';
            }
            $obj['nodes'] = self::ShowChildsFix($child);
            $obj = (object) $obj;
            $arr[] = $obj;
        }
        return $arr;
    }

    public static function ShowTreeFix()
    {
        $result = Catalog::find()->roots()->all();
        $leaves = [];
        $obj = [];
        $obj['text'] = $result['0']->name;
        $obj['id'] = $result['0']->id;
        $obj['nodes'] = self::ShowChildsFix($result['0']);
        $obj = (object) $obj;
        $leaves[] = $obj;
        return $leaves;
    }

    public static function sortTree($leaves)
    {
        $sorted = false;
        $length = count($leaves) - 1;
        //
        while ($sorted == false)
        {
            $sorted = true;
            for ($i = 0; $i < $length; $i++)
            {
                if ($leaves[$i]->name > $leaves[$i+1]->name)
                {
                    $k = $leaves[$i];
                    $leaves[$i] = $leaves[$i+1];
                    $leaves[$i+1] = $k;
                    $sorted = false;
                }
            }
        }
        return $leaves;
    }

    public static function getTreeDataForAccessGroups() {

        $leaves = Catalog::find()->all();

        return self::showTree($leaves);
    }

    public static function getTreeDataForAccessGroupsAndSelected($idsChecked = []) {

        $leaves = Catalog::find()->all();

        return self::showTree($leaves,$idsChecked);
    }

    public static function checkAndGetIdsOfGroupElements($rawIds=[]) {

        $rows = self::find()->where(['node_type'=>self::TYPE_GROUP,'id'=>$rawIds])->asArray()->all();

        $rowsData = ArrayHelper::map($rows,'id','id');
        return array_keys($rowsData);
    }

}