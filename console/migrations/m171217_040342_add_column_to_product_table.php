<?php

use yii\db\Migration;

/**
 * Class m171217_040342_add_column_to_product_table
 */
class m171217_040342_add_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','type_id',$this->integer());
        $this->addColumn('product','property_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product','type_id');
        $this->dropColumn('product','property_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171217_040342_add_column_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
