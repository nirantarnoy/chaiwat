<?php

use yii\db\Migration;

/**
 * Handles adding sale_id to table `warehouse`.
 */
class m171107_143955_add_sale_id_column_to_warehouse_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('warehouse','sale_id',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('warehouse','sale_id');
    }
}
