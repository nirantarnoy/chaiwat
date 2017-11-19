<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transtable`.
 */
class m171114_012421_create_transtable_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transtable', [
            'id' => $this->primaryKey(),
            'transno' => $this->string(),
            'trans_type'=> $this->integer(),
            'transdate' => $this->integer(),
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
        $this->dropTable('transtable');
    }
}
