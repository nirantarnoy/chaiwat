<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_tmp".
 *
 * @property int $id
 * @property string $product_code
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property int $category_id
 * @property double $weight
 * @property string $unit_id
 * @property string $brand_id
 * @property string $model_id
 * @property string $parent_id
 * @property string $price
 * @property double $cost
 * @property double $sale_price_1
 * @property double $sale_price_2
 * @property double $qty
 * @property double $min_qty
 * @property double $max_qty
 * @property int $status
 * @property double $product_start
 * @property double $sale_qty
 * @property double $purch_qty
 * @property double $return_qty
 * @property double $adjust_qty
 * @property double $cost_sum
 * @property string $group_id
 * @property string $vendor_id
 * @property double $front_qty
 * @property double $back_qty
 * @property double $back_qty2
 * @property double $total_qty
 * @property string $selection
 * @property int $mode
 * @property double $sale_price
 * @property string $type_id
 * @property string $property_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class ProductTmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_tmp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'mode', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['weight', 'price', 'cost', 'sale_price_1', 'sale_price_2', 'qty', 'min_qty', 'max_qty', 'product_start', 'sale_qty', 'purch_qty', 'return_qty', 'adjust_qty', 'cost_sum', 'front_qty', 'back_qty', 'back_qty2', 'total_qty', 'sale_price'], 'number'],
            [['product_code', 'name', 'description', 'photo', 'unit_id', 'brand_id', 'model_id', 'parent_id', 'group_id', 'vendor_id', 'selection', 'type_id', 'property_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_code' => 'Product Code',
            'name' => 'Name',
            'description' => 'Description',
            'photo' => 'Photo',
            'category_id' => 'Category ID',
            'weight' => 'Weight',
            'unit_id' => 'Unit ID',
            'brand_id' => 'Brand ID',
            'model_id' => 'Model ID',
            'parent_id' => 'Parent ID',
            'price' => 'Price',
            'cost' => 'Cost',
            'sale_price_1' => 'Sale Price 1',
            'sale_price_2' => 'Sale Price 2',
            'qty' => 'Qty',
            'min_qty' => 'Min Qty',
            'max_qty' => 'Max Qty',
            'status' => 'Status',
            'product_start' => 'Product Start',
            'sale_qty' => 'Sale Qty',
            'purch_qty' => 'Purch Qty',
            'return_qty' => 'Return Qty',
            'adjust_qty' => 'Adjust Qty',
            'cost_sum' => 'Cost Sum',
            'group_id' => 'Group ID',
            'vendor_id' => 'Vendor ID',
            'front_qty' => 'Front Qty',
            'back_qty' => 'Back Qty',
            'back_qty2' => 'Back Qty2',
            'total_qty' => 'Total Qty',
            'selection' => 'Selection',
            'mode' => 'Mode',
            'sale_price' => 'Sale Price',
            'type_id' => 'Type ID',
            'property_id' => 'Property ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
