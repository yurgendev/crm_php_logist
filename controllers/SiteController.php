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

        // Получаем путь к изображениям из соответствующего поля модели Lot
        $imagesField = 'photo_' . $type;
        $images = explode(',', $lot->$imagesField);

        return $this->render('gallery', ['images' => $images, 'lot' => $lot, 'type' => $type]);
    }

    public function actionAllLots()
    {
        $query = Lot::find();

        // Фильтрация по статусу
        $status = Yii::$app->request->get('status');
        if ($status) {
            $query->andWhere(['status' => $status]);
        }

        // Поиск по VIN, Lot или Auto
        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andWhere(['or',
                ['like', 'vin', $search],
                ['like', 'lot', $search],
                ['like', 'auto', $search],
            ]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $lots = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        // Получаем все возможные статусы
        $statuses = Lot::getStatuses();

        return $this->render('all_lots', [
            'lots' => $lots,
            'pagination' => $pagination,
            'search' => $search,
            'statuses' => $statuses,
            'selectedStatus' => $status,
        ]);
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