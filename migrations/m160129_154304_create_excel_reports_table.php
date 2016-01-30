<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_154304_create_excel_reports_table extends Migration
{
    public function up()
    {
        $this->createTable('excel_reports', [
            'id' => 'pk',
            'user_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'catalog_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'base_material_id' => Schema::TYPE_INTEGER.'(11) NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('excel_reports');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
