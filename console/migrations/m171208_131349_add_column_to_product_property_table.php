<?php

use yii\db\Migration;

/**
 * Class m171208_131349_add_column_to_product_property_table
 */
class m171208_131349_add_column_to_product_property_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_property','type_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('product_property','type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_131349_add_column_to_product_property_table cannot be reverted.\n";

        return false;
    }
    */
}
