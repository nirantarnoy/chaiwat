<?php

use yii\db\Migration;

/**
 * Class m171119_040811_add_column_to_employee_tabl
 */
class m171119_040811_add_column_to_employee_tabl extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('employee','email',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('employee','email');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171119_040811_add_column_to_employee_tabl cannot be reverted.\n";

        return false;
    }
    */
}
