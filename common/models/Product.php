<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $product_code
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property int $category_id
 * @property double $weight
 * @property int $unit_id
 * @property string $price
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','category_id','product_code'],'required'],
            [['category_id', 'unit_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by','parent_id','brand_id','model_id','property_id','type_id'], 'integer'],
            [['weight', 'price','cost','sale_price_1','sale_price_2','qty','min_qty','max_qty','purch_qty','sale_qty','return_qty','adjust_qty','cost_sum','product_start'], 'number'],
            [['product_code', 'name', 'description', 'photo','selection','notes'], 'string', 'max' => 255],
            [['group_id','vendor_id','mode'],'integer'],
            [['front_qty','back_qty','back_qty2','total_qty','sale_price'],'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_code' => 'รหัสผลิตภัณฑ์',
            'name' => 'ชื่อ',
            'description' => 'รายละเอียด',
            'photo' => 'รูปภาพ',
            'category_id' => 'กลุ่ม',
            'weight' => 'น้ำหนัก',
            'unit_id' => 'หน่วย',
            'cost'=>'ราคาทุน',
            'sale_price_1'=>'ราคาขาย',
            'sale_price_2'=>'ราคาขาย',
            'price' => 'ราคา',
            'qty' => 'คงเหลือ',
            'min_qty'=>'ขั้นต่ำ',
            'max_qty'=>'สูงสุด',
            'status' => 'สถานะ',
            'brand_id' =>'ยี่ห้อ',
            'model_id' => 'รุ่นสินค้า',
            'parent_id' => 'หมวดย่อย',
            'product_start'=> 'สินค้าตั้งงวด',
            'sale_qty'=>'ขาย',
            'purch_qty'=>'ซื้อ',
            'return_qty'=>'รับคืน',
            'adjust_qty'=>'ปรับปรุง',
            'cost_sum'=>'ทุนรวม',
            'type_id' =>'ประเภท',
            'property_id'=>'ลักษณะ',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'group_id' => 'กลุ่ม',
            'vendor_id' =>'ผู้จำหน่าย',
            'front_qty'=>'หน้าร้าน',
            'back_qty'=>'หลังร้าน',
            'back_qty2'=>'หลังร้าน',
            'total_qty'=>'รวม',
            'selection'=>'รายการที่เลือก',
            'mode' => 'สั่งซื้อ',
            'sale_price' => 'ราคาขาย',
            'notes'=>'บันทึกพิเเศษ',
        ];
    }
}
