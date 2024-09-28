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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
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

        return $this->render('create', [
            'model' => $model,
            'accounts' => $accounts,
            'customers' => $customers,
            'companies' => $companies,
            'auctions' => $auctions,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        // Получаем данные для выпадающих списков
        $accounts = ArrayHelper::map(Account::find()->all(), 'id', 'name');
        $customers = ArrayHelper::map(Customer::find()->all(), 'id', 'name');
        $companies = ArrayHelper::map(Company::find()->all(), 'id', 'name');
        $auctions = ArrayHelper::map(Auction::find()->all(), 'id', 'name');
        $warehouses = ArrayHelper::map(Warehouse::find()->all(), 'id', 'name');

        return $this->render('update', [
            'model' => $model,
            'accounts' => $accounts,
            'customers' => $customers,
            'companies' => $companies,
            'auctions' => $auctions,
            'warehouses' => $warehouses,
        ]);
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
