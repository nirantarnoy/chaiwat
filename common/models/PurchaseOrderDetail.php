<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase_order_detail".
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int $product_id
 * @property int $qty
 * @property double $price
 * @property double $line_amount
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_id','product_id'],'required'],
            [['purchase_order_id', 'product_id', 'qty', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['price', 'line_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_order_id' => 'เลขที่ใบสั่งซื้อ',
            'product_id' => 'รหัสสินค้า',
            'qty' => 'จำนวน',
            'price' => 'ราคา',
            'line_amount' => 'จำนวน',
            'status' => 'สถานะ',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
