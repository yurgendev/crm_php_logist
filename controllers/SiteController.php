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
use app\models\Auction;
use app\models\LotSearch;


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
        $searchModel = new LotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $statuses = Lot::getStatuses();
        $customers = Customer::find()->all();
        $warehouses = Warehouse::find()->all();
        $companies = Company::find()->all();

        return $this->render('all_lots', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => $statuses,
            'customers' => $customers,
            'warehouses' => $warehouses,
            'companies' => $companies,
        ]);
    }


    public function actionUpdateLot($id)
    {
        $model = $this->findModel($id);

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

    protected function processFiles($uploadedFiles, $existingFiles, $type)
    {
        $savedFileNames = [];
        foreach ($uploadedFiles as $file) {
            $fileName = uniqid() . '.' . $file->extension;
            $filePath = Yii::getAlias('@webroot/uploads/' . $type . '/' . $fileName);
            if ($file->saveAs($filePath)) {
                $savedFileNames[] = $fileName;
            }
        }
        $existingFilesArray = $existingFiles ? explode(',', $existingFiles) : [];
        $allFiles = array_merge($existingFilesArray, $savedFileNames);
        return implode(',', $allFiles);
    }


    public function actionNew()
{
    $searchModel = new LotSearch();
    $searchModel->status = 'new'; 

    Yii::info('Search model status: ' . $searchModel->status, __METHOD__);

    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    Yii::info('Data provider: ' . print_r($dataProvider, true), __METHOD__);


    $auctions = Auction::find()->all();
    $customers = Customer::find()->all();
    $warehouses = Warehouse::find()->all();

    return $this->render('new', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'auctions' => $auctions,
        'customers' => $customers,
        'warehouses' => $warehouses,
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
    $searchModel = new LotSearch();
    $searchModel->status = 'dispatched'; // Устанавливаем статус для фильтрации

    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    // Получение данных для фильтров
    $auctions = Auction::find()->all();
    $customers = Customer::find()->all();
    $warehouses = Warehouse::find()->all();

    return $this->render('dispatched', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'auctions' => $auctions,
        'customers' => $customers,
        'warehouses' => $warehouses,
    ]);
}

public function actionTerminal()
{
    $searchModel = new LotSearch();
    $searchModel->status = 'terminal'; // Устанавливаем статус для фильтрации

    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    // Получение данных для фильтров
    $customers = Customer::find()->all();
    $warehouses = Warehouse::find()->all();
    $companies = Company::find()->all();

    return $this->render('terminal', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'customers' => $customers,
        'warehouses' => $warehouses,
        'companies' => $companies,
    ]);
}

    public function actionLoading()
    {
        $searchModel = new LotSearch();
        $searchModel->status = 'loading'; // Устанавливаем статус для фильтрации
    
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        // Получение данных для фильтров
        $customers = Customer::find()->all();
        $warehouses = Warehouse::find()->all();
        $companies = Company::find()->all();
    
        return $this->render('loading', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customers' => $customers,
            'warehouses' => $warehouses,
            'companies' => $companies,
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
    $lot = $this->findModel($id);

    if (!$lot) {
        throw new NotFoundHttpException('Лот не найден.');
    }

    $fileField = $type;
    $pdfFile = $lot->$fileField;

    // Определяем директорию в зависимости от типа файла
    $directories = [
        'bos' => 'uploads/bos',
        'title' => 'uploads/title',
    ];

    if (!isset($directories[$type])) {
        throw new NotFoundHttpException('Invalid type specified.');
    }

    $filePath = Yii::getAlias('@webroot') . '/' . $directories[$type] . '/' . $pdfFile;

    Yii::info("Trying to access file: $filePath", __METHOD__);

    if (!$pdfFile || !file_exists($filePath)) {
        Yii::error("File not found: $filePath", __METHOD__);
        throw new NotFoundHttpException('File not found.');
    }

    return $this->render('view-pdf', [
        'pdfFile' => $directories[$type] . '/' . $pdfFile,
        'lot' => $lot,
        'type' => $type,
    ]);
}
    
    protected function findModel($id)
    {
        if (($model = Lot::findOne($id)) !== null) {
            return $model;
        }
    
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    



    public function actionDeleteFile()
{
    $id = Yii::$app->request->post('id');
    $type = Yii::$app->request->post('type');
    $file = Yii::$app->request->post('key'); // Используем 'key', так как плагин отправляет 'key'

    if (!$id || !$type || !$file) {
        throw new NotFoundHttpException('Некорректные параметры удаления.');
    }

    $lot = $this->findModel($id);
    if (!$lot) {
        throw new NotFoundHttpException('Лот не найден.');
    }

    $fileField = $type;
    $files = explode(',', $lot->$fileField);

    // Удаляем файл из массива
    if (($key = array_search($file, $files)) !== false) {
        unset($files[$key]);
        // Обновляем поле в модели
        $lot->$fileField = implode(',', $files);
        if ($lot->save(false)) {
            $filePath = Yii::getAlias('@webroot/uploads/' . $type . '/' . $file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return true; 
        } else {
            return false; 
        }
    } else {
        throw new NotFoundHttpException('Файл не найден.');
    }
}
    
    }