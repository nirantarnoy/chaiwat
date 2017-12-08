<?php

use yii\db\Migration;

/**
 * Class m171207_142816_add_column_to_product_table
 */
class m171207_142816_add_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product','mode',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product','mode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_142816_add_column_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
