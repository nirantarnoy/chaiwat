<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transline`.
 */
class m171114_012430_create_transline_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transline', [
            'id' => $this->primaryKey(),
            'trans_id'=>$this->integer(),
            'emp_id' => $this->integer(),
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
        $this->dropTable('transline');
    }
}
