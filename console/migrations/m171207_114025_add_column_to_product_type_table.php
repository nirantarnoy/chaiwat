<?php

use yii\db\Migration;

/**
 * Class m171207_114025_add_column_to_product_type_table
 */
class m171207_114025_add_column_to_product_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_type','group_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('product_type','group_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_114025_add_column_to_product_type_table cannot be reverted.\n";

        return false;
    }
    */
}
