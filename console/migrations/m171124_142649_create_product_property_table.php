<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_property`.
 */
class m171124_142649_create_product_property_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_property', [
            'id' => $this->primaryKey(),
             'name' => $this->string(),
            'description'=>$this->string(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_property');
    }
}
