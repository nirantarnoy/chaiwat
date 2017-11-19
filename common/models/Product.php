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
            [['category_id', 'unit_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by','parent_id','brand_id','model_id'], 'integer'],
            [['weight', 'price','cost','sale_price_1','sale_price_2','qty','min_qty','max_qty'], 'number'],
            [['product_code', 'name', 'description', 'photo'], 'string', 'max' => 255],
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
            'category_id' => 'หมวดผลิตภัณฑ์',
            'weight' => 'น้ำหนัก',
            'unit_id' => 'หน่วย',
            'cost'=>'ราคาทุน',
            'sale_price_1'=>'ราคาขาย',
            'sale_price_2'=>'ราคาขาย',
            'price' => 'ราคา',
            'qty' => 'จำนวน',
            'min_qty'=>'ขั้นต่ำ',
            'max_qty'=>'สูงสุด',
            'status' => 'สถานะ',
            'brand_id' =>'ยี่ห้อ',
            'model_id' => 'รุ่นสินค้า',
            'parent_id' => 'หมวดผลิตภัณฑ์ย่อย',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
