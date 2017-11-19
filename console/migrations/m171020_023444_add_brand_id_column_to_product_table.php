<?php

use yii\db\Migration;

/**
 * Handles adding brand_id to table `product`.
 */
class m171020_023444_add_brand_id_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product','brand_id',$this->integer());
        $this->addColumn('product','model_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product','brand_id');
        $this->dropColumn('product','model_id');
    }
}
