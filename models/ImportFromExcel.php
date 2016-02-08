<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 27.01.2016
 * Time: 17:16
 */
namespace app\models;

use Yii;
use yii\db\Query;

class ImportFromExcel extends \yii\base\Model
{
    public $settings;
    public $filename;
    public $id_group;

    public function import()
    {
        $path_info = pathinfo($this->filename);
        if ($path_info['extension'] == 'xls')
            $xlsReader = \PHPExcel_IOFactory::createReader('Excel5');
        else
            $xlsReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $xlsObj = $xlsReader->load($this->filename);
        $xlsObj->setActiveSheetIndex(0);
        $sheet = $xlsObj->getActiveSheet();
        $this->deleteAllRows();
        $ids = $this->importBaseSettings($sheet);
        $this->importExtendedSettings($sheet, $this->settings, $this->id_group, $ids);

    }

    //Удаляет все строки из таблицы характеристик и материалов
    public function deleteAllRows()
    {
        $catalog = Catalog::findOne(['id' => $this->id_group]);

        //Берем id записей из таблицы характеристик
        $ids = (new Query())
            ->select('id')
            ->from($catalog->table_name)
            ->all();
        //Удаляем записи из таблицы характеристик
        $sql = Yii::$app->db->createCommand()
            ->delete($catalog->table_name, ['id' => $ids])
            ->execute();
        //Удаляем записи из таблицы материалов по id
        $sql = Yii::$app->db->createCommand()
            ->delete(BaseMaterial::tableName(), ['id' => $ids])
            ->execute();
    }

    //Импорт базовых характеристик, возвращает массив id insert-ов
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
        //Количество строк в таблице
        $rowsCount = $sheet->getHighestRow();
        for ($i = 1; $i <= $rowsCount; $i++)
        {
            foreach ($arr as $key => $value)
            {
                if ($this->settings[$key]['nameExcelColumn'] != '')
                {
                    $row[$key] = $sheet->getCell($this->settings[$key]['nameExcelColumn'].$i)->getValue();
                }
                else
                {
                    $row[$key] = '';
                }
            }
            $rows[] = $row;
        }
        foreach ($rows as $row)
        {
            $sql = Yii::$app->db->createCommand()
                ->insert(BaseMaterial::tableName(), $row)
                ->execute();
            $ids[] = Yii::$app->db->lastInsertID;
        }
        return $ids;
    }

    public function importExtendedSettings($sheet, $settings, $id, $ids)
    {
        $tableName = Catalog::findOne(['id' => $this->id_group])->table_name;
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
        $j = 0;
        for ($i = 1; $i <= $rowsCount; $i++)
        {
            foreach ($arr as $key => $value)
            {
                if ($this->settings[$key]['nameExcelColumn'] != '')
                {
                    $row[$key] = $sheet->getCell($settings[$key]['nameExcelColumn'] . $i)->getValue();
                }
                else
                    $row[$key] = '';
            }
            $row['id'] = $ids[$j];
            $j++;
            $rows[] = $row;
        }
        foreach ($rows as $row)
        {
            $sql = Yii::$app->db->createCommand()
                ->insert($tableName, $row)
                ->execute();
        }
        $x = 1;
    }
}