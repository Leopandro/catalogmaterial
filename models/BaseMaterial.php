<?php

namespace app\models;

use PHPExcel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\mysql\Schema;
use yii\db\Query;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
/**
 * This is the model class for table "base_material".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_verify
 * @property string $source
 * @property string $code
 * @property string $note
 */
class BaseMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_verify','name','source','code','note'], 'safe'],
            [['name', 'source', 'code', 'note'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'date_verify' => 'Дата сверки',
            'source' => 'Источник',
            'code' => 'Шифр',
            'note' => 'Примечание',
        ];
    }

    public static function getAttributesArray()
    {
        return [
            ['Наименование','Дата сверки','Источник','Шифр','Примечание']
        ];
    }

//    public function afterSave()
//    {
//        return $this->id;
//    }

    public function filterSearch($params, $id)
    {
        $catalog = Catalog::findOne(['id' => $id]);
        $query = (new Query())
            ->select('*')
            ->from($catalog->table_name);
        if ($params['name']['value'])
            $baseMaterialIds = (new Query())
                ->select('id')
                ->from(BaseMaterial::tableName())
                ->where(['like', 'name', $params['name']['value']])
                ->all();
        if (is_array($baseMaterialIds))
        {
            foreach($baseMaterialIds as $id)
            {
                $ids[] = $id['id'];
            }
            $query->andWhere(['id' => $ids]);
        }
        foreach ($params as $param)
        {
            if ($param['label'] != 'name'){
                if ($param['type_value'] == '1') {
                    if ($param['value'] != '')
                        $query->where(['like', $param['label'], $param['value']]);
                }
                else {
                    if(($param['firstcompare'] == '<' || $param['firstcompare'] == '<=' || $param['firstcompare'] == '=') && $param['firstvalue'] != '') {
                        if($param['secondcompare'] != '' && $param['secondvalue'] != ''){
                            $query->where([
                                'or',
                                $param['label'].$param['firstcompare'].$param['firstvalue'],
                                $param['label'].$param['secondcompare'].$param['secondvalue']
                            ]);
                               // ->andWhere([$param['secondcompare'], $param['label'], $param['secondvalue']]);
                        }
                        else{
                            $query->where([$param['firstcompare'], $param['label'], $param['firstvalue']]);
                        }
                    }
                    elseif($param['firstcompare'] == '>' || $param['firstcompare'] == '>=' && $param['firstvalue'] != '')
                    {
                        if($param['secondcompare'] != '' && $param['secondvalue'] != ''){
                            $query->where([
                                'and',
                                $param['label'].$param['firstcompare'].$param['firstvalue'],
                                $param['label'].$param['secondcompare'].$param['secondvalue']
                            ]);
                        }
                        else{
                            $query->where([$param['firstcompare'], $param['label'], $param['firstvalue']]);
                        }
                    }
                    elseif($param['firstcompare'] == '' || $param['firstvalue'] == '') {
                        if ($param['secondcompare'] != '' || $param['secondvalue'] != '')
                            $query->where([$param['secondcompare'], $param['label'], $param['secondvalue']]);
                    };
                }
            }
        }
        $rows = $query->all();
        $ids = [];
        foreach ($rows as $row)
        {
            $ids[] = $row['id'];
        }
        return new ActiveDataProvider([
            'query' => BaseMaterial::find()->where(['id' => $ids]),
        ]);
    }

    public function search() {


        $query = self::find();

        if($this->date_verify) {
            $query->where( [
                'OR',['<', 'date_verify', date("Y-m-d 00:00:00",strtotime($this->date_verify))],['date_verify'=>null]
            ]);
        }


        return new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort'=>[
                'attributes'=>[
                    'name',
                    'note',
                    'source',
                    'code',
                    'date_verify'
                ]
            ]

        ]);
    }

    public static function updateTable($group)
    {
        $columns = CharacteristicGroup::findAll(['id_group' => $group->id]);
        $sql = Yii::$app->db->createCommand();

        //Проверка есть ли в таблице столбец с заданным label-ом
        //если нет - добавляем
        foreach ($columns as $column)
        {
            if (!$thiscolumn = Yii::$app->db->schema->getTableSchema($group->table_name)->columns[$column->label])
            {
                if ($column->type_value == 0)
                    $valueType = Schema::TYPE_INTEGER.'(11) NULL';
                elseif ($column->type_value == 1)
                    $valueType = Schema::TYPE_STRING.'(255) NULL';
                elseif ($column->type_value == 2)
                    $valueType = Schema::TYPE_DECIMAL.'(11,2) NULL';
                $sql->addColumn($group->table_name, $column->label, $valueType)->execute();
            }
            else
            {
                $type = $thiscolumn->type; // decimal string integer
                $columntype = $column->type_value; // 0 = integer 1 = string 2 = decimal
                if ($columntype == 0)
                {
                    $columntype = 'integer';
                    $sqlType = Schema::TYPE_INTEGER.'(11) NULL';
                }
                if ($columntype == 1)
                {
                    $columntype = 'string';
                    $sqlType = Schema::TYPE_STRING.'(255) NULL';
                }
                if ($columntype == 2)
                {
                    $columntype = 'decimal';
                    $sqlType = Schema::TYPE_DECIMAL.'(11,2) NULL';
                }
                if ($type != $columntype)
                {
                    //поменять на $columntype
                    $sql->alterColumn($group->table_name, $column->label, $sqlType)->execute();
                }
            }
        }
        $columnsInTable = Yii::$app->db->schema->getTableSchema($group->table_name)->getColumnNames();

        //Проверка, есть ли такой столбец в списке характеристик
        //Если нет - удаляем
        foreach($columnsInTable as $columnInTable)
        {
            if ($columnInTable != 'id')
            {
                $haveColumn = false;
                foreach ($columns as $column)
                {
                    if ($columnInTable == $column->label)
                        $haveColumn = true;
                }
                if ($haveColumn == false)
                {
                    $sql->dropColumn($group->table_name, $columnInTable)->execute();
                }
            }
        }
        //Проверка соответствия типов
    }

    public static function createTable($group)
    {
        $columns = CharacteristicGroup::findAll(['id_group' => $group->id]);
        // Создаем таблицу
        $sql = Yii::$app->db->createCommand();
        $sql->createTable($group->table_name, [
            'id' => 'pk',
        ])->execute();

        //Добавляем колонки
        foreach ($columns as $column)
        {
            //$columnName = Yii::$app->security->generateRandomString(11);
            if ($column->type_value == 0)
                $valueType = Schema::TYPE_INTEGER.'(11) NULL';
            elseif ($column->type_value == 1)
                $valueType = Schema::TYPE_STRING.'(255) NULL';
            elseif ($column->type_value == 2)
                $valueType = Schema::TYPE_DECIMAL.'(11,2) NULL';
            $sql->addColumn($group->table_name, $column->label, $valueType)->execute();
        }

        //и связываем id c id base_material
        $sql->addForeignKey(Yii::$app->security->generateRandomString(), $group->table_name, 'id', 'base_material', 'id', 'NO ACTION', 'NO ACTION')
            ->execute();
    }

    //Вывод отчета в excel
    public static function getReports()
    {
        $materials = ExcelReport::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy('catalog_id')
            ->all();
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(30);
//        $sheet->getColumnDimension('B')->setWidth(20);
//        $sheet->getColumnDimension('C')->setWidth(22);
//        $sheet->getColumnDimension('D')->setWidth(25);
//        $sheet->getColumnDimension('E')->setWidth(22);

        //перевод строки
        foreach (range('A','Z') as $columnName){
            $sheet->getStyle($columnName)->getAlignment()->setWrapText(true);
            $sheet->getStyle($columnName)->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle($columnName)->getAlignment()->setVertical(
                PHPExcel_Style_Alignment::VERTICAL_TOP);
        }
        //установка ширины
        foreach (range('B','Z') as $columnName){
            $sheet->getColumnDimension($columnName)->setWidth(15);
        }
        $lastCatalogId = 0;
        for ($i = 0; $i < count($materials); $i++)
        {
            if ($materials[$i]['catalog_id'] != $lastCatalogId){
                $i == 0 ? $highestrow = $sheet->getHighestRow() : $highestrow = $sheet->getHighestRow()+1;
                $catalog = Catalog::findOne(['id' => $materials[$i]['catalog_id']]);
                $sheet->mergeCells('A'.$highestrow.':'.'B'.$highestrow);
                $sheet->setCellValue('A'.$highestrow, $catalog->name);
                $sheet->getStyle('A'.$highestrow)->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFFF80')
                    ]
                ]);
                $lastCatalogId = $materials[$i]['catalog_id'];
                //заполняем названия столбцов
                {
                    $highestrow = $sheet->getHighestRow() + 1;
                    $materialColumnName = self::getAttributesArray()[0][0];
                    $characteristicColumnNames = self::getNamesOnly($catalog);
                    //Вводим 'наименование'
                    $j = 0;
                    $cellAddress = chr(ord('A')+$j).$highestrow;
                    $sheet->setCellValue($cellAddress, $materialColumnName);
                    $j++;

                    foreach ($characteristicColumnNames as $columnName)
                    {
                        $cellAddress = chr(ord('A')+$j).$highestrow;
                        $sheet->setCellValue($cellAddress, $columnName);
                        $j++;
                    }
                }
                //заполняем значения
                {
                    $highestrow = $sheet->getHighestRow() + 1;
                    $materialAttributes = BaseMaterial::findOne(['id' => $materials[$i]['base_material_id']]);
                    $j = 0;
//                    foreach($materialAttributes as $key => $value)
//                    {
//                        if ($key == 'id')
//                        {
                                $cellAddress = chr(ord('A')+$j).$highestrow;
                                $sheet->setCellValue($cellAddress, $materialAttributes['name']);
                                $j++;
//                        }
//                    }
                    $materialCharacteristics = (new Query())
                        ->select('*')
                        ->from($catalog->table_name)
                        ->where(['id' => $materials[$i]['base_material_id']])
                        ->one();
                    foreach($materialCharacteristics as $key => $value)
                    {
                        if ($key != 'id')
                        {
                            $cellAddress = chr(ord('A')+$j).$highestrow;
                            $sheet->setCellValue($cellAddress, $value);
                            $j++;
                        }
                    }
                    $lastCatalogId = $materials[$i]['catalog_id'];
                }
            }
            else{
                $catalog = Catalog::findOne(['id' => $materials[$i]['catalog_id']]);
                //заполняем названия столбцов

                //заполняем значения
                $highestrow = $sheet->getHighestRow() + 1;
                $materialAttributes = BaseMaterial::findOne(['id' => $materials[$i]['base_material_id']]);
                $j = 0;
//                    foreach($materialAttributes as $key => $value)
//                    {
//                        if ($key == 'id')
//                        {
                $cellAddress = chr(ord('A')+$j).$highestrow;
                $sheet->setCellValue($cellAddress, $materialAttributes['name']);
                $j++;
//                        }
//                    }
                $materialCharacteristics = (new Query())
                    ->select('*')
                    ->from($catalog->table_name)
                    ->where(['id' => $materials[$i]['base_material_id']])
                    ->one();

                Yii::warning('data '.print_r($materialCharacteristics,true));

                foreach($materialCharacteristics as $key => $value)
                {
                    if ($key != 'id')
                    {
                        $cellAddress = chr(ord('A')+$j).$highestrow;
                        Yii::warning('cell address '.$cellAddress.' value of cell: '.$value);
                        $sheet->setCellValue($cellAddress, $value);
                        $j++;
                    }
                }
                $lastCatalogId = $materials[$i]['catalog_id'];
            }
        }

        $delete = (new Query())
            ->createCommand()
            ->delete(ExcelReport::tableName(), ['user_id' => Yii::$app->user->identity->id])
            ->execute();

        $filename = 'Report_materials_'.Yii::$app->user->identity->username.'_'.date('d-m-Y_H:i:s', time());
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename.'.xls');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        $writer->save('php://output');
    }

    public static function getExcelReport()
    {
        $user_id = Yii::$app->user->identity->id;
//        $path = Yii::getAlias('@app/runtime/uploads/').Yii::$app->user->identity->id.'/';
//        $xslPath = Yii::getAlias('@app/runtime/uploads/').Yii::$app->user->identity->id.'/'.'report.xls';
        $xsl = new PHPExcel();
        $xsl->setActiveSheetIndex(0);
        $sheet = $xsl->getActiveSheet();
        $rows = (new Query())
            ->select(['catalog_id', 'base_material_id'])
            ->from(ExcelReport::tableName())
            ->where(['user_id' => $user_id])
            ->all();
        if ($rows) {
            foreach ($rows as $row) {
                $group = Catalog::findOne(['id' => $row['catalog_id']]);
                $material = BaseMaterial::findOne(['id' => $row['base_material_id']]);
                $labelsForSearch = self::getLabelsOnly($group);
                $characteristic = self::getLabels($group);
                $charQuery = (new Query())
                    ->select($labelsForSearch)
                    ->from($group->table_name)
                    ->where(['id' => $row['base_material_id']])
                    ->one();
                for ($i = 0; $i < count($characteristic); $i++) {
                    $characteristic[$i]['value'] = $charQuery[$characteristic[$i]['label']];
                }
                $highestRow = $sheet->getHighestRow();
                $sheet->mergeCells('A' . ($highestRow) . ':' . 'B' . ($highestRow));
                $sheet->setCellValue('A' . ($highestRow), $group->name . '. ' . $material->name);
                for ($i = 0; $i < count($characteristic); $i++) {
                    $sheet->setCellValue('A' . ($highestRow + $i + 1), $characteristic[$i]['name']);
                    $sheet->setCellValue('B' . ($highestRow + $i + 1), $characteristic[$i]['value']);
                }
            }
            $delete = (new Query())
                ->createCommand()
                ->delete(ExcelReport::tableName(), ['user_id' => Yii::$app->user->identity->id])
                ->execute();
            $filename = 'Report_materials_'.Yii::$app->user->identity->username.'_'.date('d-m-Y_H:i:s', time());
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$filename.'.xls');
            header('Cache-Control: max-age=0');

            $writer = \PHPExcel_IOFactory::createWriter($xsl, 'Excel5');
            $writer->save('php://output');
        }
    }

    /* Берем столбцы из excel документа */
    public static function getColumns($filename)
    {
        $excel = \PHPExcel_IOFactory::createReader('Excel5');

        $excel = $excel->load($filename);
        $excel->setActiveSheetIndex(0);

        $i = 0;
        while(true)
        {
            $cellName = chr(ord('A')+$i).'1';
            $cell = $excel->getActiveSheet()->getCell($cellName);
            if ($cell == '')
            {
                break;
            }
            else
                $cells[] = $cell;
            $i++;
        }
        return $cells;
    }
    /* Получаем id записей из таблицы(сгенерированной)*/
    public static function getIds($table_name)
    {
        $rows = (new Query())
            ->select('id')
            ->from($table_name)
            ->all();
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }


    public static function getNamesOnly($group)
    {
        $labels = (new Query())
            ->select('name')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        foreach ($labels as $label)
        {
            $arr[] = $label['name'];
        }
        return $arr;
    }

    public static function getLabelsOnly($group)
    {
        $labels = (new Query())
            ->select('label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        foreach ($labels as $label)
        {
            $arr[] = $label['label'];
        }
        return $arr;
    }

    /* Получаем имена и лейблы из таблицы характеристик групп по id группы*/
    public static function getLabels($group)
    {
        $labels = (new Query())
            ->select('name,label')
            ->from(CharacteristicGroup::tableName())
            ->where(['id_group' => $group->id])
            ->all();
        return $labels;
    }

    /* Получаем строку из сгенерированной таблицы по id*/
    public static function getRow($columns, $group, $id)
    {
        $rows = (new Query())
            ->select($columns)
            ->from($group->table_name)
            ->where(['id' => $id])
            ->one();
        return $rows;
    }

    /* Получаем все строки из сгенерированной таблицы*/
    public static function getRows($group)
    {
        $rows = (new Query())
            ->select("*")
            ->from($group->table_name)
            ->all();
        return $rows;
    }

    /* Получаем строки из base_material */
    public static function getMaterials($ids)
    {
        $rows = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where(['id' => $ids])
            ->all();
        return $rows;
    }
    /* Получить характеристики экземпляра base_material*/
    public static function getModel($group, $material_id)
    {
        $labels = self::getLabels($group);
        foreach ($labels as $label) {
            $columns[] = $label['label'];
        }
        $basematerial = BaseMaterial::find()->where(['id' => $material_id])->one();
        foreach ($basematerial as $key => $value)
        {
            if ($key != 'date_verify')
                $obj[$key] = $value;
            else
            {
                if ($value == '' || $value == NULL)
                    $obj[$key] = $value;
                elseif($time = strtotime($value))
                    $obj[$key] = date('d.m.Y', $time);
            }
        }
        $rows = self::getRow($columns, $group, $material_id);
        foreach ($labels as $label) {
            $obj[$label['label']] = $rows[$label['label']];
        }
        return ($model = (Object)$obj);
    }

    public static function createModel($group_id, $attributes)
    {
        $baseMaterial = new BaseMaterial();
        foreach ($attributes as $key => $value)
        {
            if ($value == '')
                $attributes[$key] = NULL;
        }
        $baseMaterial->setAttributes($attributes);
        if(!$baseMaterial->save())
            return false;
        $id_pk = $baseMaterial->id;
        $catalog = Catalog::findOne(['id' => $group_id]);
        $extra_table_name = $catalog->table_name;
        $column_names = self::getLabelsOnly($catalog);
        $columns['id'] = $id_pk;
        foreach ($attributes as $key => $value)
        {
            if ($key != 'id')
            {
                foreach ($column_names as $column_name)
                {
                    if ($key == $column_name)
                        $columns[$key] = $value;
                    break;
                }
            }
        }
        $insert = Yii::$app->db->createCommand()
            ->insert($extra_table_name, $columns)
            ->execute();
    }

    public static function updateModel($group_id,$material_id,$attributes) {

        $baseMaterial = BaseMaterial::findOne(['id'=>$material_id]);
        if(!$baseMaterial)
            return false;

        if(strtotime($attributes['date_verify']))
            $attributes['date_verify'] = date("Y-m-d ".date("H:i:s"),strtotime($attributes['date_verify'] ));

        $baseMaterial->setAttributes($attributes);
        if(!$baseMaterial->save())
            return false;

        $groupData = Catalog::findOne(['id'=>$group_id]);
        if(!$groupData)
            return false;

        $attributesRelatedTable = array_diff_key($attributes,array_merge($baseMaterial->attributes,['group_id'=>$group_id]));


        $resultUpdate = BaseMaterial::getDb()->createCommand()
            ->update($groupData->table_name, $attributesRelatedTable, 'id=:id_material',[':id_material'=>$material_id])
            ->execute();

        return true;
    }


    /* Получаем таблицу из таблицы материалов и характеристик для xsl*/
    public static function getModels($group)
    {
        $arr = self::getAttributesArray();
        $ids = self::getIds($group->table_name);
        $materials = self::getMaterials($ids);
        $j = 0;
        foreach ($materials as $material)
        {
            $j++;
            $material = array_values($material);
            for ($i = 1; $i < count($material); $i++)
            {
                if ($i == 2)
                {
                    $arr[$j][$i-1] = date('d.m.Y', strtotime($material[$i]));
                }
                else
                    $arr[$j][$i-1] = $material[$i];
            }
        }
        $labels = self::getLabels($group);
        $rows = self::getRows($group);
        $i = 5;
        foreach ($labels as $label)
        {
            $arr[0][$i] = $label['name'];
            $i++;
        }

        $j = 1;
        foreach ($rows as $row)
        {
            $row = array_values($row);
            for ($i = 1; $i < count($row);$i++)
            {
                $arr[$j][$i+5] = $row[$i];
            }
            $j++;
        }
        return $arr;
    }

    /* Получаем колонки с именами и лейблами */
    public static function getColumnsAndLabels($group)
    {
        if (Yii::$app->user->identity->role_id == 1)
        {
            $columns = self::getBaseMaterialLabels();
            $labelsCharacteritic = self::getLabels($group);
            $i = 5;
            foreach ($labelsCharacteritic as $label) {
                $columns[$i]['label'] = $label['name'];
                $columns[$i]['attribute'] = $label['label'];
                $i++;
            }
            return $columns;
        }
        else
        {
            $columns = [
                [
                    'attribute' => 'name',
                    'label' => 'Наименование',
                    'rules_validate'=>[
                        ['name','required'],
                        ['name','string','max'=>128]
                    ],
                    'type_widget'=>'textfield'
                ]
            ];
            $labelsCharacteritic = self::getLabels($group);
            $i = 1;
            foreach ($labelsCharacteritic as $label) {
                $columns[$i]['label'] = $label['name'];
                $columns[$i]['attribute'] = $label['label'];
                $i++;
            }
            return $columns;
        }
    }

    /* Массив атрибутов и лейблов для detailview */
    public static function getBaseMaterialLabels()
    {
        return [
            [
                'attribute' => 'name',
                'label' => 'Наименование',
                'rules_validate'=>[
                    ['name','required'],
                    ['name','string','max'=>128]
                ],
                'type_widget'=>'textfield'
            ],
            [
                'attribute' => 'date_verify',
                'label' => 'Дата верификации',
                'rules_validate'=>[
                    ['date_verify','string','max'=>128]
                ],
                'type_widget'=>'datepicker'
            ],
            [
                'attribute' => 'source',
                'label' => 'Источник',
                'rules_validate'=>[
                    ['source','string','max'=>255]
                ],
                'type_widget'=>'textfield'
            ],
            [
                'attribute' => 'code',
                'label' => 'Шифр',
                'rules_validate'=>[
                    ['code','string','max'=>50]
                ],
                'type_widget'=>'textfield'
            ],
            [
                'attribute' => 'note',
                'label' => 'Примечание',
                'rules_validate'=>[
                    ['note','string','max'=>255]
                ],
                'type_widget'=>'textfield'
            ],
        ];
    }


    public static function getAttributesByGroupId($idGroup) {


        $baseMaterialAttributes = self::getBaseMaterialLabels();
        $baseMaterialAttributes[] = [
            'attribute'=>'id',
            'label'=>'',
            'rules_validate'=>[],
            'type_widget'=>'hidden'
        ];

        $baseMaterialAttributes[] = [
            'attribute'=>'group_id',
            'label'=>'',
            'rules_validate'=>[],
            'type_widget'=>'hidden'
        ];

        $characteristicsOfGroup = CharacteristicGroup::findAll(['id_group'=>$idGroup]);
        if(is_array($characteristicsOfGroup)) {

            foreach($characteristicsOfGroup as $curCharacteristic) {

                $dataAttr = [
                    'attribute'=>$curCharacteristic->label,
                    'label'=>$curCharacteristic->name,
                ];


                switch($curCharacteristic->type_value) {

                    case 0:
                        $dataAttr['rules_validate'] = [
                            [$curCharacteristic->label,'integer']
                        ];
                        break;
                    case 1:
                        $dataAttr['rules_validate'] = [
                            [$curCharacteristic->label,'string','max'=>255]
                        ];
                        break;
                    case 2:
                        $dataAttr['rules_validate'] = [
                            [$curCharacteristic->label,'double']
                        ];
                        break;
                }

                if(intval($curCharacteristic->is_required) == 1)
                    $dataAttr['rules_validate'][] = [$curCharacteristic->label,'required'];

                $dataAttr['type_widget'] = 'textfield';

                $baseMaterialAttributes[] = $dataAttr;

            }

        }

        return $baseMaterialAttributes;
    }

}
