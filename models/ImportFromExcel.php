<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 27.01.2016
 * Time: 17:16
 */
namespace app\models;

use yii\db\Query;

class ImportFromExcel extends \yii\base\Model
{
    public $settings;
    //public $startRow;
    public $filename;
    public $id_group;

    public function import()
    {
        $xlsReader = \PHPExcel_IOFactory::createReader('Excel5');
        $xlsObj = $xlsReader->load($this->filename);
        $xlsObj->setActiveSheetIndex(0);
        $sheet = $xlsObj->getActiveSheet();
        $ids = self::importBaseSettings($sheet);
        self::importExtendedSettings($sheet, $this->settings, $this->id_group, $ids);
        $x = 1;

    }
    public function importBaseSettings($sheet)
    {
        $attributes = (new BaseMaterial())->attributeLabels();
        foreach($attributes as $key => $value)
        {
            if ($key != 'id')
            {
                $arr[$key] = ' ';
            }
        }

        $rowsCount = $sheet->getHighestRow();
        for ($i = 2; $i < $rowsCount + 1; $i++)
        {
            foreach ($arr as $key => $value)
            {
                $row[$key] = $sheet->getCell($this->settings[$key]['nameExcelColumn'].$i)->getValue();
            }
            $rows[] = $row;
        }
        $ids = [];
        return $ids;
    }

    public static function importExtendedSettings($sheet, $settings, $id, $ids)
    {
        $settingRows = (new Query())
            ->select('label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $id])
            ->all();
        foreach ($settingRows as $setting)
        {
            $key = $setting['label'];
            $arr[$key] = '';
        }
        $rowsCount = $sheet->getHighestRow();
        for ($i = 2; $i < $rowsCount + 1; $i++)
        {
            foreach ($arr as $key => $value)
            {
                $row[$key] = $sheet->getCell($settings[$key]['nameExcelColumn'].$i)->getValue();
            }
            $rows[] = $row;
        }
        $x = 1;
    }
}