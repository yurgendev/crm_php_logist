<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lot;

/**
 * LotSearch represents the model behind the search form of `app\models\Lot`.
 */
class LotSearch extends Lot
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'auction_id', 'customer_id', 'warehouse_id', 'company_id', 'has_keys'], 'integer'],
            [['bos', 'photo_a', 'photo_d', 'photo_w', 'video', 'title', 'photo_l', 'status', 'status_changed', 'date_purchase', 'date_warehouse', 'payment_date', 'date_booking', 'data_container', 'date_unloaded', 'auto', 'vin', 'lot', 'url', 'line', 'booking_number', 'container', 'ata_data'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Lot::find();

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
            'status_changed' => $this->status_changed,
            'date_purchase' => $this->date_purchase,
            'date_warehouse' => $this->date_warehouse,
            'payment_date' => $this->payment_date,
            'date_booking' => $this->date_booking,
            'data_container' => $this->data_container,
            'date_unloaded' => $this->date_unloaded,
            'account_id' => $this->account_id,
            'auction_id' => $this->auction_id,
            'customer_id' => $this->customer_id,
            'warehouse_id' => $this->warehouse_id,
            'company_id' => $this->company_id,
            'price' => $this->price,
            'has_keys' => $this->has_keys,
            'ata_data' => $this->ata_data,
        ]);

        $query->andFilterWhere(['like', 'bos', $this->bos])
            ->andFilterWhere(['like', 'photo_a', $this->photo_a])
            ->andFilterWhere(['like', 'photo_d', $this->photo_d])
            ->andFilterWhere(['like', 'photo_w', $this->photo_w])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'photo_l', $this->photo_l])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'auto', $this->auto])
            ->andFilterWhere(['like', 'vin', $this->vin])
            ->andFilterWhere(['like', 'lot', $this->lot])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'line', $this->line])
            ->andFilterWhere(['like', 'booking_number', $this->booking_number])
            ->andFilterWhere(['like', 'container', $this->container]);

        return $dataProvider;
    }
}
