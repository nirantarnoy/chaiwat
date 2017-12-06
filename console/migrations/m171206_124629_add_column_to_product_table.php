<?php

use yii\db\Migration;

/**
 * Class m171206_124629_add_column_to_product_table
 */
class m171206_124629_add_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','group_id',$this->integer());
        $this->addColumn('product','vendor_id',$this->integer());
        $this->addColumn('product','front_qty',$this->float());
        $this->addColumn('product','back_qty',$this->float());
        $this->addColumn('product','back_qty2',$this->float());
        $this->addColumn('product','total_qty',$this->float());
        $this->addColumn('product','selection',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product','group_id');
        $this->dropColumn('product','vendor_id');
        $this->dropColumn('product','front_qty');
        $this->dropColumn('product','back_qty');
        $this->dropColumn('product','back_qty2');
        $this->dropColumn('product','total_qty');
        $this->dropColumn('product','selection');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171206_124629_add_column_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
