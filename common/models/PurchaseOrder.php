<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase_order".
 *
 * @property int $id
 * @property string $purchase_order
 * @property int $vendor_id
 * @property int $purchase_date
 * @property int $required_date
 * @property string $note
 * @property double $purchase_amount
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['purchase_order'],'required'],
            [['vendor_id', 'required_date', 'status','created_by', 'updated_by'], 'integer'],
            [['purchase_amount'], 'number'],
            [['purchase_date', 'created_at', 'updated_at', ],'safe'],
            [['purchase_order', 'note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_order' => 'เลขที่ใบสั่งซื้อ',
            'vendor_id' => 'ผู้ขาย',
            'purchase_date' => 'วันที่สั่งซื้อ',
            'required_date' => 'วันที่ต้องการ',
            'note' => 'บันทึก',
            'purchase_amount' => 'ยอดรวม',
            'status' => 'สถานะ',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
