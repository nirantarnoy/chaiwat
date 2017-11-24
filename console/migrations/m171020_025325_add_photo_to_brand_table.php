<?php

use yii\db\Migration;

/**
 * Class m171020_025325_add_photo_to_brand_table
 */
class m171020_025325_add_photo_to_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('brand','photo',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       $this->dropColumn('brand','photo');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171020_025325_add_photo_to_brand_table cannot be reverted.\n";

        return false;
    }
    */
}
