<?php

use yii\db\Migration;

/**
 * Handles the creation of table `purchase_order`.
 */
class m171030_032904_create_purchase_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('purchase_order', [
            'id' => $this->primaryKey(),
            'purchase_order' => $this->string(),
            'vendor_id' => $this->integer(),
            'purchase_date' => $this->integer(),
            'required_date' => $this->integer(),
            'note' => $this->string(),
            'purchase_amount' => $this->float(),
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
        $this->dropTable('purchase_order');
    }
}
