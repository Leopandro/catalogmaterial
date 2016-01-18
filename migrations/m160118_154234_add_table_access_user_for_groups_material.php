<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_154234_add_table_access_user_for_groups_material extends Migration
{
    public function up()
    {
        $this->createTable('{{%access_user_group_material}}', [
            'id' => Schema::TYPE_PK.' AUTO_INCREMENT',
            'id_user' => Schema::TYPE_INTEGER,
            'id_group_material' => Schema::TYPE_INTEGER,
            'is_allow' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
        ]);

        $this->addForeignKey("access_user_group_material_user_fk", "{{%access_user_group_material}}", "id_user", "{{%user}}", "id", 'NO ACTION');
        $this->addForeignKey("access_user_group_material_material_fk", "{{%access_user_group_material}}", "id_group_material", "{{%catalog}}", "id", 'NO ACTION');
    }

    public function down()
    {

        $this->dropForeignKey('access_user_group_material_user_fk','{{%access_user_group_material}}');
        $this->dropForeignKey('access_user_group_material_material_fk','{{%access_user_group_material}}');
        $this->dropTable('{{%access_user_group_material}}');

        return true;
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
