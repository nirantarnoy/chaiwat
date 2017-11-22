<?php

use yii\db\Migration;

/**
 * Class m171122_014324_add_column_to_product_table
 */
class m171122_014324_add_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','product_start',$this->float());
        $this->addColumn('product','sale_qty',$this->float());
        $this->addColumn('product','purch_qty',$this->float());
        $this->addColumn('product','return_qty',$this->float());
        $this->addColumn('product','adjust_qty',$this->float());
        $this->addColumn('product','cost_sum',$this->float());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product','product_start');
        $this->dropColumn('product','sale_qty');
        $this->dropColumn('product','purch_qty');
        $this->dropColumn('product','return_qty');
        $this->dropColumn('product','adjust_qty');
        $this->dropColumn('product','cost_sum');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171122_014324_add_column_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
