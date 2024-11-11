<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

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

    const STATUS_NEW = 'new';
    const STATUS_DISPATCHED = 'dispatched';
    const STATUS_TERMINAL = 'terminal';
    const STATUS_LOADING = 'loading';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_UNLOADED = 'unloaded';
    const STATUS_ARCHIVED = 'archived';


    public static function tableName()
    {
        return 'Lot';
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_DISPATCHED => 'Dispatched',
            self::STATUS_TERMINAL => 'Terminal',
            self::STATUS_LOADING => 'Loading',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_UNLOADED => 'Unloaded',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Проверяем, изменился ли статус
            if ($this->isAttributeChanged('status')) {
                // Устанавливаем текущий сценарий в зависимости от нового статуса
                $this->setScenario($this->status);

                // Устанавливаем текущую дату и время для status_changed
                $this->status_changed = date('Y-m-d H:i:s');

                // Проверяем валидность модели в текущем сценарии
                if (!$this->validate()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Вычисляет количество дней, прошедших с момента изменения статуса.
     *
     * @return int Количество дней
     */
    public function getWait()
    {
        if ($this->status_changed) {
            $statusChangedDate = new \DateTime($this->status_changed);
            $currentDate = new \DateTime();
            $interval = $statusChangedDate->diff($currentDate);
            return $interval->days + 1;
        }
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auto', 'vin', 'lot', 'date_purchase', 'status'], 'required'],
            [['bosFiles', 'photoAFiles', 'photoDFiles', 'photoWFiles', 'videoFiles', 'titleFiles', 'photoLFiles'], 'file', 'maxFiles' => 25],
            [['account_id', 'auction_id', 'customer_id', 'warehouse_id', 'company_id', 'has_keys'], 'integer'],
            [['price'], 'number'],
            [['status_changed', 'date_purchase', 'date_warehouse', 'payment_date', 'date_booking', 'date_container', 'date_unloaded', 'ata_data', 'date_dispatch'], 'safe'],
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
            [['status'], 'validateStatusTransition'], 
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::STATUS_NEW] = ['status', 'payment_date'];
        $scenarios[self::STATUS_DISPATCHED] = ['status', 'date_warehouse'];
        $scenarios[self::STATUS_TERMINAL] = ['status', 'date_booking'];
        $scenarios[self::STATUS_LOADING] = ['status', 'date_container'];
        $scenarios[self::STATUS_SHIPPED] = ['status', 'date_unloaded'];
        $scenarios[self::STATUS_UNLOADED] = ['status'];
        return $scenarios;
    }

    public function validateStatusTransition($attribute, $params)
    {
        switch ($this->status) {
            case self::STATUS_DISPATCHED:
                if (empty($this->payment_date)) {
                    $this->addError('payment_date', 'Payment date must be set to transition to dispatched.');
                }
                break;
            case self::STATUS_TERMINAL:
                if (empty($this->date_warehouse)) {
                    $this->addError('date_warehouse', 'Warehouse date must be set to transition to terminal.');
                }
                break;
            case self::STATUS_LOADING:
                if (empty($this->date_booking)) {
                    $this->addError('date_booking', 'Booking date must be set to transition to loading.');
                }
                break;
            case self::STATUS_SHIPPED:
                if (empty($this->date_container)) {
                    $this->addError('date_container', 'Container date must be set to transition to shipped.');
                }
                break;
            case self::STATUS_UNLOADED:
                if (empty($this->date_unloaded)) {
                    $this->addError('date_unloaded', 'Unloaded date must be set to transition to unloaded.');
                }
                break;
        }
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
            'date_container' => 'Date Container',
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
            'account.name' => 'Account',
            'auction.name' => 'Auction',
            'customer.name' => 'Customer',
            'warehouse.name' => 'Warehouse',
            'company.name' => 'Company',
            'date_dispatch' => 'Date Dispatch',
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

    // подсчет количества файлов
    public function getBosFileCount()
    {
        return $this->getFileCount($this->bos);
    }

    public function getPhotoAFileCount()
    {
        return $this->getFileCount($this->photo_a);
    }

    public function getPhotoDFileCount()
    {
        return $this->getFileCount($this->photo_d);
    }

    public function getPhotoWFileCount()
    {
        return $this->getFileCount($this->photo_w);
    }

    public function getVideoFileCount()
    {
        return $this->getFileCount($this->video);
    }

    public function getTitleFileCount()
    {
        return $this->getFileCount($this->title);
    }

    public function getPhotoLFileCount()
    {
        return $this->getFileCount($this->photo_l);
    }

    private function getFileCount($files)
    {
        if (empty($files)) {
            return 0;
        }
        return count(explode(',', $files));
    }


    public function search($params)
    {
        $query = self::find();

        // Создаем ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5, // Количество элементов на странице
            ],
        ]);

        // Загружаем параметры поиска и применяем фильтры
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Применение фильтров по фотографиям
        if ($this->photoA_filter === 'Yes') {
            $query->andWhere(['not', ['photo_a' => null]]);
            $query->andWhere(['<>', 'photo_a', '']);
        } elseif ($this->photoA_filter === 'No') {
            $query->andWhere(['or', ['photo_a' => null], ['photo_a' => '']]);
        }

        if ($this->photoD_filter === 'Yes') {
            $query->andWhere(['not', ['photo_d' => null]]);
            $query->andWhere(['<>', 'photo_d', '']);
        } elseif ($this->photoD_filter === 'No') {
            $query->andWhere(['or', ['photo_d' => null], ['photo_d' => '']]);
        }

        if ($this->photoW_filter === 'Yes') {
            $query->andWhere(['not', ['photo_w' => null]]);
            $query->andWhere(['<>', 'photo_w', '']);
        } elseif ($this->photoW_filter === 'No') {
            $query->andWhere(['or', ['photo_w' => null], ['photo_w' => '']]);
        }

        if ($this->photoL_filter === 'Yes') {
            $query->andWhere(['not', ['photo_l' => null]]);
            $query->andWhere(['<>', 'photo_l', '']);
        } elseif ($this->photoL_filter === 'No') {
            $query->andWhere(['or', ['photo_l' => null], ['photo_l' => '']]);
        }

        // Фильтрация по статусу
        if ($this->status) {
            $query->andWhere(['status' => $this->status]);
        }

        // Фильтрация по Customer
        if ($this->customer_id) {
            $query->andWhere(['customer_id' => $this->customer_id]);
        }

        // Фильтрация по Warehouse
        if ($this->warehouse_id) {
            $query->andWhere(['warehouse_id' => $this->warehouse_id]);
        }

        // Фильтрация по Company
        if ($this->company_id) {
            $query->andWhere(['company_id' => $this->company_id]);
        }

        // Поиск по VIN, Lot или Auto
        if ($this->search) {
            $query->andWhere([
                'or',
                ['like', 'vin', $this->search],
                ['like', 'lot_number', $this->search],
                ['like', 'auto', $this->search],
            ]);
        }

        return $dataProvider;
    }



    public function getInitialPreview($type)
    {
        $files = $this->$type ? explode(',', $this->$type) : [];
        $initialPreview = [];
        foreach ($files as $file) {
            $initialPreview[] = Yii::getAlias('@web/uploads/' . $type . '/' . $file);
        }
        return $initialPreview;
    }

    public function getInitialPreviewConfig($type)
    {
        $files = $this->$type ? explode(',', $this->$type) : [];
        $initialPreviewConfig = [];
        foreach ($files as $file) {
            $initialPreviewConfig[] = [
                'caption' => basename($file),
                'url' => Url::to(['/site/delete-file']), // Убедитесь, что путь указан правильно
                'key' => $file,
                'extra' => [
                    'id' => $this->id,
                    'type' => $type,
                    'file' => $file,
                ],
            ];
        }
        return $initialPreviewConfig;
    }

    

}



       



