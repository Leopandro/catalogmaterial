<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_135029_create_characteristic_group extends Migration
{
    public function up()
    {
        $this->dropTable('characteristic_group');
        $this->createTable('characteristic_group', [
            'id' => 'pk',
            'name' => 'VARCHAR(255) NULL DEFAULT NULL',
            'label' => 'VARCHAR(255) NULL DEFAULT NULL',
            'type_value' => 'INT(11) NOT NULL DEFAULT \'0\'',
            'is_required' => 'INT(1) NOT NULL DEFAULT \'0\'',
            'id_group' => 'INT(1) NOT NULL DEFAULT \'0\'',
            'is_visible' => 'INT(1) NOT NULL DEFAULT \'0\'',
        ]);
        $this->addForeignKey('fk_characteristic_group_catalog_id', 'characteristic_group', 'id_group', 'catalog', 'id', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('characteristic_group');
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
