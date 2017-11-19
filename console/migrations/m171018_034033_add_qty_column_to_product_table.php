<?php

use yii\db\Migration;

/**
 * Handles adding qty to table `product`.
 */
class m171018_034033_add_qty_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product','qty',$this->float());
        $this->addColumn('product','min_qty',$this->float());
        $this->addColumn('product','max_qty',$this->float());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product','qty');
        $this->dropColumn('product','min_qty');
        $this->dropColumn('product','max_qty');
    }
}
