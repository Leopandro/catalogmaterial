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

class Catalog extends ActiveRecord
{
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

    public  static function showChilds($leaves, $depth)
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
                }

                $dataNode['text']=$leave->name;
                $dataNode['id']=$leave->id;

                $dataNode['nodes'] = self::showChilds($leaves, $leave->id);

                $result[] = (object)$dataNode;
            }
        }

        return $result;
    }
    public  static function  showTree($leaves)
    {
        $result = [];
        foreach($leaves as $leave)
        {
            if($leave->depth == 0)
                $result[] = (object)['id'=>$leave->id,'text'=>$leave->name,'nodes'=>self::showChilds($leaves, $leave->id)];
        }
        return $result;
    }
}