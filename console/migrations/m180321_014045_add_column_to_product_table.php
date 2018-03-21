<?php

use yii\db\Migration;

/**
 * Class m180321_014045_add_column_to_product_table
 */
class m180321_014045_add_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','notes',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('product','notes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180321_014045_add_column_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
