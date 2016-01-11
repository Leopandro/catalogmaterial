<?php

namespace app\models;

use Yii;
use paulzi\adjacencylist\AdjacencyListBehavior;

/**
 * This is the model class for table "shop_categories".
 *
 * @property string $id
 * @property string $opencart_id
 * @property string $name
 * @property string $parent_id
 * @property integer $status
 */
class ShopCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opencart_id', 'parent_id', 'status'], 'integer'],
            [['name', 'status'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'opencart_id' => 'номер из интернет-магазина',
            'name' => 'название',
            'parent_id' => 'родительская категория',
            'status' => 'статус',
        ];
    }

	public function beforeSave($insert)
	{

		if (!isset($this->parent_id) OR $this->parent_id=='') {
			$this->parent_id = 0;
		}

		return parent::beforeSave($insert);
	}

    public function behaviors() {
        return [
            [
                'class' => AdjacencyListBehavior::className(),
                'parentAttribute'=>'parent_id',
                'childrenJoinLevels'=>10,
                'parentsJoinLevels'=>5,
                'sortAttribute'=>'id'
            ],
        ];
    }


    public static function find()
    {
        return new ShopCategoriesQuery(get_called_class());
    }

	public function getParentCategory($parentId)
	{
		$parent = ShopCategories::find()->where(['id' => $parentId])->one();
		return  $parent['name'];
	}

	public static function getCategoryName($categoryId)
	{
		$category = ShopCategories::find()->where(['id' => $categoryId])->one();
		return  $category['name'];
	}

	public static function getTree()
	{
		$items = [
			[
				'text' => 'Parent 1',
				'nodes' => [
					[
						'text' => 'Child 1',
						'nodes' => [
							[
								'text' => 'Grandchild 1'
							],
							[
								'text' => 'Grandchild 2'
							]
						]
					],
					[
						'text' => 'Child 2',
					]
				],
			],
			[
				'text' => 'Parent 2',
			]
		];
		$categories = ShopCategories::find()->asArray()->all();

		$parents = ShopCategories::getChilds($categories,0);

		return $parents;
		//return $items;
	}

	public static function getChilds($array,$id)
	{
		foreach ($array as $item) {
			if ($item['parent_id']==$id) $child[] = array('text' => $item['name'], 'href' => '#'.$item['id']);
		}
		if (isset($child)) {
			foreach ($child as $k => $v) {
				$v['id'] = intval(substr($v['href'],1));
				if (ShopCategories::getChilds($array,$v['id'])!='')
					$child[$k]['nodes'] = ShopCategories::getChilds($array,$v['id']);
				}
			return $child;
		}
		else return '';
	}


    public static function getSorteredAndNestedItemsForDropDownList() {

        $result = array();
        $roots = ShopCategories::find()->roots()->orderBy('name')->all();

        foreach($roots as $cur_root) {
            $result[$cur_root->id] = $cur_root->name;
            $descendants = $cur_root->getDescendants()->orderBy('name')->all();
            foreach($descendants as $cur_descendant) {
                $result[$cur_descendant->id] = $cur_descendant->name;
            }
        }
        return $result;
    }


    public static function getNestedChildsCategoryIds($parentId) {

        $node = ShopCategories::findOne(['id'=>$parentId]);
        if($node)
            return array_merge($node->getDescendantsIds(null, true),array($parentId));

        return array($parentId);
    }

}
