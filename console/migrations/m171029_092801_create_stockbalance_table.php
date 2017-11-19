<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stockbalance`.
 */
class m171029_092801_create_stockbalance_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stockbalance', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'qty' => $this->integer(),
            'warehouse_id' => $this->integer(),
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
        $this->dropTable('stockbalance');
    }
}
