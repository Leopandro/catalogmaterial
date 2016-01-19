<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_141956_add_characteristic_group_temp extends Migration
{
    public function up()
    {
        $this->createTable('characteristic_group_temp', [
            'id' => 'pk',
            'name' => 'VARCHAR(255) NULL DEFAULT NULL',
            'label' => 'VARCHAR(255) NULL DEFAULT NULL',
            'name_table' => 'VARCHAR(255) NULL DEFAULT NULL',
            'type_value' => 'INT(11) NOT NULL DEFAULT \'0\'',
            'is_required' => 'INT(1) NOT NULL DEFAULT \'0\'',
            'is_visible' => 'INT(1) NOT NULL DEFAULT \'0\'',
            'id_user' => 'INT(11) NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('characteristic_group_temp');
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
