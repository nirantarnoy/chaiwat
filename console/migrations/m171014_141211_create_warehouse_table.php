<?php

use yii\db\Migration;

/**
 * Handles the creation of table `warehouse`.
 */
class m171014_141211_create_warehouse_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('warehouse', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'description' => $this->string(),
            'is_default'=>$this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('warehouse');
    }
}
