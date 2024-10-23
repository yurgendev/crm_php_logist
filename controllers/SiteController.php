<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Lot;
use app\models\Customer;
use app\models\Warehouse;
use app\models\Company;
use yii\data\Pagination;

class SiteController extends Controller
{
    public function actionAllLots()
    {
        $query = Lot::find();

        // Фильтрация по статусу
        $status = Yii::$app->request->get('status');
        if ($status) {
            $query->andWhere(['status' => $status]);
        }

        // Фильтрация по Customer
        $customerId = Yii::$app->request->get('customer_id');
        if ($customerId) {
            $query->andWhere(['customer_id' => $customerId]);
        }

        // Фильтрация по Warehouse
        $warehouseId = Yii::$app->request->get('warehouse_id');
        if ($warehouseId) {
            $query->andWhere(['warehouse_id' => $warehouseId]);
        }

        // Фильтрация по Company
        $companyId = Yii::$app->request->get('company_id');
        if ($companyId) {
            $query->andWhere(['company_id' => $companyId]);
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

        // Получаем всех клиентов
        $customers = Customer::find()->select(['id', 'name'])->orderBy('name')->all();

        // Получаем все склады
        $warehouses = Warehouse::find()->select(['id', 'name'])->orderBy('name')->all();

        // Получаем все компании
        $companies = Company::find()->select(['id', 'name'])->orderBy('name')->all();

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
        ]);
    }
}