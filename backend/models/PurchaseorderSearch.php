<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Purchaseorder;

/**
 * PurchaseorderSearch represents the model behind the search form of `backend\models\Purchaseorder`.
 */
class PurchaseorderSearch extends Purchaseorder
{
    public $globalSearch;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vendor_id', 'purchase_date', 'required_date', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['purchase_order', 'note'], 'safe'],
            [['purchase_amount'], 'number'],
             [['globalSearch'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Purchaseorder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'purchase_date' => $this->purchase_date,
            'required_date' => $this->required_date,
            'purchase_amount' => $this->purchase_amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

         if($this->globalSearch != ''){
            $query->orFilterWhere(['like','purchase_order',$this->globalSearch])
                  ->orFilterWhere(['like','note',$this->globalSearch]);
        }

        return $dataProvider;
    }
}
