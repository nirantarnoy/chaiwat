<?php

use yii\db\Migration;

/**
 * Handles the creation of table `purchase_order_detail`.
 */
class m171030_032911_create_purchase_order_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('purchase_order_detail', [
            'id' => $this->primaryKey(),
            'purchase_order_id' =>$this->integer(),
            'product_id' => $this->integer(),
            'qty' => $this->integer(),
            'price' => $this->float(),
            'line_amount' => $this->float(),
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
        $this->dropTable('purchase_order_detail');
    }
}
