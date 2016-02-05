<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 27.01.2016
 * Time: 15:55
 */

namespace app\models;

use Yii;
use yii\base\Model;

class ImportModel extends Model
{
    public $columnSettings;
    public $file;
    //public $startRow;
    public static $columnNamesExcel = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
        'F' => 'F',
        'G' => 'G',
        'H' => 'H',
        'I' => 'I',
        'J' => 'J',
        'K' => 'K',
        'L' => 'L',
        'M' => 'M',
        'N' => 'N',
        'O' => 'O',
        'P' => 'P',
        'Q' => 'Q',
        'R' => 'R',
        'S' => 'S',
        'T' => 'T',
        'U' => 'U',
        'V' => 'V',
        'W' => 'W',
        'X' => 'X',
        'Y' => 'Y',
        'Z' => 'Z'
    ];
    public function rules()
    {
        return [
            ['file', 'required', 'message'=>'Необходимо загрузить excel файл'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx, csv'],
            //[['startRow'], 'required'],
            [['columnSettings'], 'required']
        ];
    }
    public function upload()
    {
        $path = Yii::getAlias('@app/runtime/uploads/').Yii::$app->user->identity->id.'/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $filename = Yii::$app->security->generateRandomString(9);
        $this->file->saveAs($path . $filename . '.' . $this->file->extension);
        return $path . $filename . '.' . $this->file->extension;
    }
    public function generateSettings($id)
    {
        $columns = CharacteristicGroup::find()->where(['id_group' => $id])->all();
        $this->getBaseSettings();
        foreach ($columns as $column)
        {
            $this->columnSettings[$column->name] = [
                'name' => $column->label, 'label' => $column->name, 'nameExcelColumn' => ''
            ];
        }
        //return $this->columnSettings;
    }
    public function getBaseSettings()
    {
        $baseSettings =(new BaseMaterial())->attributeLabels();
        foreach ($baseSettings as $key => $value)
        {
            if ($key != 'id')
            {
                $this->columnSettings[$key] = ['label' => $value, 'name' => $key, 'nameExcelColumn' => ''];
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Excel файл',
            'startRow' => 'Номер строки первой записи'
        ];
    }
}