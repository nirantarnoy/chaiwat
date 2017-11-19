<?php

use yii\db\Migration;

/**
 * Class m171115_134631_add_column_to_transline_table
 */
class m171115_134631_add_column_to_transline_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
         $this->addColumn('transline','title_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
         $this->dropColumn('transline','amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171115_134631_add_column_to_transline_table cannot be reverted.\n";

        return false;
    }
    */
}
