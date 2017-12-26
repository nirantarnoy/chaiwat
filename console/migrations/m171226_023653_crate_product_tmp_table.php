<?php

use yii\db\Migration;

/**
 * Class m171226_023653_crate_product_tmp_table
 */
class m171226_023653_crate_product_tmp_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
          $this->createTable('product_tmp', [
            'id' => $this->primaryKey(),
            'product_code' => $this->string(),
            'name' => $this->string(),
            'description' => $this->string(),
            'photo' => $this->string(),
            'category_id' => $this->integer(),
            'weight' => $this->float(),
            'unit_id' => $this->string(),
            'brand_id' => $this->string(),
            'model_id' => $this->string(),
            'parent_id' => $this->string(),
            'price' => $this->money(),
            'cost' => $this->float(),
            'sale_price_1' => $this->float(),
            'sale_price_2' => $this->float(),
            'qty' => $this->float(),
            'min_qty' => $this->float(),
            'max_qty' => $this->float(),
            'status' => $this->integer(),
            'product_start'=>$this->float(),
            'sale_qty'=>$this->float(),
            'purch_qty'=>$this->float(),
            'return_qty'=>$this->float(),
            'adjust_qty'=>$this->float(),
            'cost_sum'=>$this->float(),
            'group_id'=>$this->string(),
            'vendor_id'=>$this->string(),
            'front_qty'=>$this->float(),
            'back_qty'=>$this->float(),
            'back_qty2'=>$this->float(),
            'total_qty'=>$this->float(),
            'selection'=>$this->string(),
            'mode'=>$this->integer(),
            'sale_price'=>$this->float(),
            'type_id'=>$this->string(),
            'property_id'=>$this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_tmp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171226_023653_crate_product_tmp_table cannot be reverted.\n";

        return false;
    }
    */
}
