<?php

use yii\db\Migration;

/**
 * Class m171014_131354_add_cost_sale_price_to_product_table
 */
class m171014_131354_add_cost_sale_price_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','cost',$this->float());
        $this->addColumn('product','sale_price_1',$this->float());
        $this->addColumn('product','sale_price_2',$this->float());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('product','cost');
       $this->dropColumn('product','sale_price_1');
       $this->dropColumn('product','sale_price_2');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171014_131354_add_cost_sale_price_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
