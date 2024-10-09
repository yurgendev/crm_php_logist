<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Lot;
use app\models\Account;
use app\models\Customer;
use app\models\Company;
use app\models\Auction;
use app\models\LotSearch;
use app\models\Warehouse;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->is_admin;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Lot models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LotSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Добавляем загрузку связанных данных
        if ($dataProvider->query instanceof \yii\db\ActiveQuery) {
            $dataProvider->query->with(['account', 'auction', 'customer', 'warehouse', 'company']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lot model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Lot();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Ошибка сохранения модели: ' . json_encode($model->errors));
            }
        }

        // Получаем данные для выпадающих списков
        $accounts = ArrayHelper::map(Account::find()->all(), 'id', 'name');
        $customers = ArrayHelper::map(Customer::find()->all(), 'id', 'name');
        $companies = ArrayHelper::map(Company::find()->all(), 'id', 'name');
        $auctions = ArrayHelper::map(Auction::find()->all(), 'id', 'name');
        $warehouses = ArrayHelper::map(Warehouse::find()->all(), 'id', 'name');
        $statuses = $this->getStatuses();

        return $this->render('create', [
            'model' => $model,
            'accounts' => $accounts,
            'customers' => $customers,
            'companies' => $companies,
            'auctions' => $auctions,
            'warehouses' => $warehouses,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Updates an existing Lot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->bosFiles = UploadedFile::getInstances($model, 'bosFiles');
            $model->photoAFiles = UploadedFile::getInstances($model, 'photoAFiles');
            $model->photoDFiles = UploadedFile::getInstances($model, 'photoDFiles');
            $model->photoWFiles = UploadedFile::getInstances($model, 'photoWFiles');
            $model->videoFiles = UploadedFile::getInstances($model, 'videoFiles');
            $model->titleFiles = UploadedFile::getInstances($model, 'titleFiles');
            $model->photoLFiles = UploadedFile::getInstances($model, 'photoLFiles');

            if ($model->save()) {
                $model->bos = $this->mergeFilePaths($model->bos, $this->saveFiles($model->bosFiles, 'uploads/bos'));
                $model->photo_a = $this->mergeFilePaths($model->photo_a, $this->saveFiles($model->photoAFiles, 'uploads/photo_a'));
                $model->photo_d = $this->mergeFilePaths($model->photo_d, $this->saveFiles($model->photoDFiles, 'uploads/photo_d'));
                $model->photo_w = $this->mergeFilePaths($model->photo_w, $this->saveFiles($model->photoWFiles, 'uploads/photo_w'));
                $model->video = $this->mergeFilePaths($model->video, $this->saveFiles($model->videoFiles, 'uploads/video'));
                $model->title = $this->mergeFilePaths($model->title, $this->saveFiles($model->titleFiles, 'uploads/title'));
                $model->photo_l = $this->mergeFilePaths($model->photo_l, $this->saveFiles($model->photoLFiles, 'uploads/photo_l'));

                $model->save(false); // Сохраняем модель без валидации, так как данные уже валидированы

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Ошибка сохранения модели: ' . json_encode($model->errors));
                // Вывод ошибок валидации
                var_dump($model->errors);
                die();
            }
        }

        // Получаем данные для выпадающих списков
        $accounts = ArrayHelper::map(Account::find()->all(), 'id', 'name');
        $customers = ArrayHelper::map(Customer::find()->all(), 'id', 'name');
        $companies = ArrayHelper::map(Company::find()->all(), 'id', 'name');
        $auctions = ArrayHelper::map(Auction::find()->all(), 'id', 'name');
        $warehouses = ArrayHelper::map(Warehouse::find()->all(), 'id', 'name');
        $statuses = $this->getStatuses();

        return $this->render('update', [
            'model' => $model,
            'accounts' => $accounts,
            'customers' => $customers,
            'companies' => $companies,
            'auctions' => $auctions,
            'warehouses' => $warehouses,
            'statuses' => $statuses,
        ]);
    }


    protected function mergeFilePaths($existingPaths, $newPaths)
    {
        $existingPathsArray = $existingPaths ? explode(',', $existingPaths) : [];
        $mergedPathsArray = array_merge($existingPathsArray, $newPaths);
        return implode(',', $mergedPathsArray);
    }
    /**
     * Получает статусы из базы данных.
     * @return array
     */
    protected function saveFiles($files, $directory)
    {
        $filePaths = [];
        foreach ($files as $file) {
            $uniqueName = md5($file->baseName . time()) . '.' . $file->extension;
            $filePath = $directory . '/' . $uniqueName;
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            if ($file->saveAs($filePath)) {
                $filePaths[] = $filePath;
            }
        }
        return $filePaths; // Возвращаем массив путей к файлам
    }
    protected function getStatuses()
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema('lot');
        $column = $tableSchema->columns['status'];
        $enumValues = $column->enumValues;

        return array_combine($enumValues, $enumValues);
    }

    /**
     * Deletes an existing Lot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Lot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lot::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
