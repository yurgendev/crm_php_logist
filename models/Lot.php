<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Lot".
 *
 * @property int $id
 * @property string|null $bos
 * @property string|null $photo_a
 * @property string|null $photo_d
 * @property string|null $photo_w
 * @property string|null $video
 * @property string|null $title
 * @property string|null $photo_l
 * @property string|null $status
 * @property string|null $status_changed
 * @property string $date_purchase
 * @property string|null $date_warehouse
 * @property string|null $payment_date
 * @property string|null $date_booking
 * @property string|null $data_container
 * @property string|null $date_unloaded
 * @property string $auto
 * @property string $vin
 * @property string $lot
 * @property int|null $account_id
 * @property int|null $auction_id
 * @property int|null $customer_id
 * @property int|null $warehouse_id
 * @property int|null $company_id
 * @property string $url
 * @property float $price
 * @property int|null $has_keys
 * @property string|null $line
 * @property string|null $booking_number
 * @property string|null $container
 * @property string|null $ata_data
 *
 * @property Account $account
 * @property Auction $auction
 * @property Company $company
 * @property Customer $customer
 * @property Warehouse $warehouse
 */
class Lot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $bosFiles;
    public $photoAFiles;
    public $photoDFiles;
    public $photoWFiles;
    public $videoFiles;
    public $titleFiles;
    public $photoLFiles;
    
    public static function tableName()
    {
        return 'Lot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auto', 'vin', 'lot', 'date_purchase'], 'required'],
            [['bosFiles', 'photoAFiles', 'photoDFiles', 'photoWFiles', 'videoFiles', 'titleFiles', 'photoLFiles'], 'file', 'maxFiles' => 25],
            [['account_id', 'auction_id', 'customer_id', 'warehouse_id', 'company_id', 'has_keys'], 'integer'],
            [['price'], 'number'],
            [['status'], 'string'],
            [['status_changed', 'date_purchase', 'date_warehouse', 'payment_date', 'date_booking', 'data_container', 'date_unloaded', 'ata_data'], 'safe'],
            [['bos', 'photo_a', 'photo_d', 'photo_w', 'video', 'title', 'photo_l', 'auto', 'lot', 'line', 'booking_number', 'container'], 'string', 'max' => 255],
            [['vin'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 200],
            [['vin'], 'unique'],
            [['lot'], 'unique'],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::class, 'targetAttribute' => ['account_id' => 'id']],
            [['auction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Auction::class, 'targetAttribute' => ['auction_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::class, 'targetAttribute' => ['warehouse_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bos' => 'Bos',
            'photo_a' => 'Photo A',
            'photo_d' => 'Photo D',
            'photo_w' => 'Photo W',
            'video' => 'Video',
            'title' => 'Title',
            'photo_l' => 'Photo L',
            'status' => 'Status',
            'status_changed' => 'Status Changed',
            'date_purchase' => 'Date Purchase',
            'date_warehouse' => 'Date Warehouse',
            'payment_date' => 'Payment Date',
            'date_booking' => 'Date Booking',
            'data_container' => 'Data Container',
            'date_unloaded' => 'Date Unloaded',
            'auto' => 'Auto',
            'vin' => 'Vin',
            'lot' => 'Lot',
            'account_id' => 'Account ID',
            'auction_id' => 'Auction ID',
            'customer_id' => 'Customer ID',
            'warehouse_id' => 'Warehouse ID',
            'company_id' => 'Company ID',
            'url' => 'Url',
            'price' => 'Price',
            'has_keys' => 'Has Keys',
            'line' => 'Line',
            'booking_number' => 'Booking Number',
            'container' => 'Container',
            'ata_data' => 'Ata Data',
            'photoAFiles' => 'Photo A Files',
            'photoDFiles' => 'Photo D Files',
            'photoWFiles' => 'Photo W Files',
            'videoFiles' => 'Video Files',
            'titleFiles' => 'Title Files',
            'photoLFiles' => 'Photo L Files',
        ];
    }

    /**
     * Gets query for [[Account]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }

    /**
     * Gets query for [[Auction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuction()
    {
        return $this->hasOne(Auction::class, ['id' => 'auction_id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Warehouse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::class, ['id' => 'warehouse_id']);
    }
}
