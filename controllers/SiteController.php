<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Lot;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\models\Customer;
use app\models\Warehouse;
use app\models\Company;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'delete-image' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }





    public function actionGallery($id, $type)
    {
        $lot = Lot::findOne($id);
        if (!$lot) {
            throw new NotFoundHttpException('Lot not found.');
        }

        $directories = [
            'a' => 'uploads/photo_a',
            'd' => 'uploads/photo_d',
            'w' => 'uploads/photo_w',
            'l' => 'uploads/photo_l',
        ];

        if (!isset($directories[$type])) {
            throw new NotFoundHttpException('Invalid type specified.');
        }

        // Получаем изображения из соответствующего поля модели Lot
        $imagesField = 'photo_' . $type;
        $images = explode(',', $lot->$imagesField);

        // Создаем миниатюры и добавляем дату и время загрузки
        $thumbnails = [];
        foreach ($images as $image) {
            $thumbnailPath = 'uploads/thumbnails/' . basename($image);
            $fullImagePath = Yii::getAlias('@webroot/' . $image);
            if (!file_exists($thumbnailPath)) {
                FileHelper::createDirectory('uploads/thumbnails');
                Image::thumbnail($fullImagePath, 200, 200)
                    ->save(Yii::getAlias('@webroot/' . $thumbnailPath), ['quality' => 80]);
            }
            $thumbnails[] = [
                'path' => $thumbnailPath,
                'uploaded_at' => filemtime($fullImagePath), // Используем время последнего изменения файла
            ];
        }

        return $this->render('gallery', [
            'images' => $images,
            'thumbnails' => $thumbnails,
            'lot' => $lot,
            'type' => $type,
        ]);
    }


    public function actionDeleteImage()
    {
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type');
        $image = Yii::$app->request->post('image');

        $lot = Lot::findOne($id);
        if (!$lot) {
            Yii::$app->session->setFlash('danger', 'Лот не найден.');
            return $this->redirect(['site/gallery', 'id' => $id, 'type' => $type]);
        }

        $imagesField = 'photo_' . $type;
        $images = explode(',', $lot->$imagesField);

        // Удаляем изображение из массива
        if (($key = array_search($image, $images)) !== false) {
            unset($images[$key]);
            // Обновляем поле в модели
            $lot->$imagesField = implode(',', $images);
            if ($lot->save(false)) {
                Yii::$app->session->setFlash('success', 'Image deleted.');
            } else {
                Yii::$app->session->setFlash('danger', 'Delete error.');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Image can not be founded.');
        }

        return $this->redirect(['site/gallery', 'id' => $id, 'type' => $type]);
    }

    public function actionAllLots()
    {
        // Создаем запрос к модели Lot
        $query = Lot::find();

        // Получение значений фильтров из запроса
        $photoA_filter = Yii::$app->request->get('photoA_filter');
        $photoD_filter = Yii::$app->request->get('photoD_filter');
        $photoW_filter = Yii::$app->request->get('photoW_filter');
        $photoL_filter = Yii::$app->request->get('photoL_filter');
        $status = Yii::$app->request->get('status');
        $customerId = Yii::$app->request->get('customer_id');
        $warehouseId = Yii::$app->request->get('warehouse_id');
        $companyId = Yii::$app->request->get('company_id');
        $search = Yii::$app->request->get('search');

        // Применение фильтров по фотографиям
        if ($photoA_filter === 'Yes') {
            $query->andWhere(['not', ['photo_a' => null]]);
            $query->andWhere(['<>', 'photo_a', '']);
        } elseif ($photoA_filter === 'No') {
            $query->andWhere(['or', ['photo_a' => null], ['photo_a' => '']]);
        }

        if ($photoD_filter === 'Yes') {
            $query->andWhere(['not', ['photo_d' => null]]);
            $query->andWhere(['<>', 'photo_d', '']);
        } elseif ($photoD_filter === 'No') {
            $query->andWhere(['or', ['photo_d' => null], ['photo_d' => '']]);
        }

        if ($photoW_filter === 'Yes') {
            $query->andWhere(['not', ['photo_w' => null]]);
            $query->andWhere(['<>', 'photo_w', '']);
        } elseif ($photoW_filter === 'No') {
            $query->andWhere(['or', ['photo_w' => null], ['photo_w' => '']]);
        }

        if ($photoL_filter === 'Yes') {
            $query->andWhere(['not', ['photo_l' => null]]);
            $query->andWhere(['<>', 'photo_l', '']);
        } elseif ($photoL_filter === 'No') {
            $query->andWhere(['or', ['photo_l' => null], ['photo_l' => '']]);
        }

        // Фильтрация по статусу
        if ($status) {
            $query->andWhere(['status' => $status]);
        }

        // Фильтрация по Customer
        if ($customerId) {
            $query->andWhere(['customer_id' => $customerId]);
        }

        // Фильтрация по Warehouse
        if ($warehouseId) {
            $query->andWhere(['warehouse_id' => $warehouseId]);
        }

        // Фильтрация по Company
        if ($companyId) {
            $query->andWhere(['company_id' => $companyId]);
        }

        // Поиск по VIN, Lot или Auto
        if ($search) {
            $query->andWhere([
                'or',
                ['like', 'vin', $search],
                ['like', 'lot', $search],
                ['like', 'auto', $search],
            ]);
        }

        // Пагинация
        $countQuery = clone $query;
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $countQuery->count(),
        ]);

        $lots = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        // Получаем все возможные статусы
        $statuses = Lot::getStatuses();

        // Получаем всех клиентов
        $customers = Customer::find()->select(['id', 'name'])->orderBy('name')->all();

        // Получаем все склады
        $warehouses = Warehouse::find()->select(['id', 'name'])->orderBy('name')->all();

        // Получаем все компании
        $companies = Company::find()->select(['id', 'name'])->orderBy('name')->all();

        // Передача переменных в вьюшку
        return $this->render('all_lots', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
            'statuses' => $statuses,
            'selectedStatus' => $status,
            'customers' => $customers,
            'selectedCustomer' => $customerId,
            'warehouses' => $warehouses,
            'selectedWarehouse' => $warehouseId,
            'companies' => $companies,
            'selectedCompany' => $companyId,
            'photoA_filter' => $photoA_filter,
            'photoD_filter' => $photoD_filter,
            'photoW_filter' => $photoW_filter,
            'photoL_filter' => $photoL_filter,
        ]);
    }


    public function actionUpdateLot($id)
    {
        $model = $this->findModel($id);

        // Загружаем данные из формы
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Получаем загруженные файлы
                $bosFiles = UploadedFile::getInstances($model, 'bosFiles');
                $titleFiles = UploadedFile::getInstances($model, 'titleFiles');

                // Обрабатываем BOS файлы
                $model->bos = $this->processFiles($bosFiles, $model->bos, 'bos');

                // Обрабатываем Title файлы
                $model->title = $this->processFiles($titleFiles, $model->title, 'title');

                // Сохраняем модель
                if ($model->save(false)) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Lot updated successfully.');
                    return $this->redirect(['all-lots']);
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('update-lot', [
            'model' => $model,
        ]);
    }

    protected function processFiles($files, $existingFiles, $type)
    {
        $filePaths = [];

        if (!empty($files)) {
            // Получаем существующие файлы, если они есть
            $existingFilePaths = !empty($existingFiles) ? explode(',', $existingFiles) : [];

            foreach ($files as $file) {
                $fileName = uniqid() . '_' . $file->baseName . '.' . $file->extension;
                $filePath = 'uploads/' . $type . '/' . $fileName;
                // Сохраняем файл
                if ($file->saveAs($filePath)) {
                    $filePaths[] = $filePath;
                }
            }

            // Объединяем существующие файлы с новыми
            $allFiles = array_merge($existingFilePaths, $filePaths);
            return implode(',', $allFiles);
        }

        return $existingFiles;
    }

    protected function findModel($id)
    {
        if (($model = Lot::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionNew()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'new']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('new', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function actionUnloaded()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'unloaded']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('unloaded', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function actionDispatched()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'dispatched']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('dispatched', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function actionTerminal()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'terminal']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('terminal', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function actionLoading()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'loading']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('loading', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function actionShipped()
    {
        $search = Yii::$app->request->get('search', '');
        $query = Lot::find()->where(['status' => 'shipped']);

        if ($search) {
            $query->andFilterWhere(['like', 'vin', $search])
                ->orFilterWhere(['like', 'lot', $search])
                ->orFilterWhere(['like', 'auto', $search]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('shipped', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }


    public function actionViewPdf($id, $type)
    {
        $lot = Lot::findOne($id);
        if (!$lot) {
            throw new NotFoundHttpException('Lot not found.');
        }

        $pdfFields = [
            'bos' => 'bos',
            'title' => 'title',
        ];

        if (!isset($pdfFields[$type])) {
            throw new NotFoundHttpException('Invalid type specified.');
        }

        // Получаем путь к PDF файлу из соответствующего поля модели Lot
        $pdfField = $pdfFields[$type];
        $pdfFile = $lot->$pdfField;

        if (!$pdfFile || !file_exists($pdfFile)) {
            throw new NotFoundHttpException('File not found.');
        }

        return $this->render('view-pdf', ['pdfFile' => $pdfFile, 'lot' => $lot, 'type' => $type]);
    }
}
