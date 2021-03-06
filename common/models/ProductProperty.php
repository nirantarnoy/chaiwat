<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_property".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class ProductProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         [['name'],'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by','type_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'คุณสมบัติ',
            'description' => 'รายละเอียด',
            'status' => 'สถานะ',
            'type_id'=>'ประเภทสินค้า',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
