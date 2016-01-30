<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "excel_reports".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $caatalog_id
 * @property integer $base_material_id
 */
class ExcelReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'excel_reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'caatalog_id', 'base_material_id'], 'required'],
            [['user_id', 'caatalog_id', 'base_material_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'caatalog_id' => 'Caatalog ID',
            'base_material_id' => 'Base Material ID',
        ];
    }
}
