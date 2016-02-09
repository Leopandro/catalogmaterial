<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_150454_varchar_basematerial_255 extends Migration
{
    public function up()
    {
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'name', Schema::TYPE_STRING.'(255) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'source', Schema::TYPE_STRING.'(255) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'code', Schema::TYPE_STRING.'(255) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'note', Schema::TYPE_STRING.'(255) NULL');
    }

    public function down()
    {
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'name', Schema::TYPE_STRING.'(45) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'source', Schema::TYPE_STRING.'(45) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'code', Schema::TYPE_STRING.'(45) NULL');
        $this->alterColumn(\app\models\BaseMaterial::tableName(), 'note', Schema::TYPE_STRING.'(45) NULL');
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
