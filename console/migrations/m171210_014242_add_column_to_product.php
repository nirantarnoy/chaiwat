<?php

use yii\db\Migration;

/**
 * Class m171210_014242_add_column_to_product
 */
class m171210_014242_add_column_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','sale_price',$this->float());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('product','sale_price');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171210_014242_add_column_to_product cannot be reverted.\n";

        return false;
    }
    */
}
