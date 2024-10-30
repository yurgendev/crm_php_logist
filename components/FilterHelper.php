<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class FilterHelper
{
    public static function renderFilterForm($name, $selectedValue, $options, $actionUrl, $additionalHiddenFields = [])
    {
        // Получаем все GET параметры
        $hiddenFields = Yii::$app->request->get();

        // Убираем текущий фильтр из скрытых полей
        unset($hiddenFields[$name]);

        // Добавляем дополнительные скрытые поля
        $hiddenFields = array_merge($hiddenFields, $additionalHiddenFields);

        $form = Html::beginForm($actionUrl, 'get', ['class' => 'd-inline']);
        foreach ($hiddenFields as $fieldName => $fieldValue) {
            if ($fieldValue !== null && $fieldName !== 'page') {
                $form .= Html::hiddenInput($fieldName, $fieldValue);
            }
        }
        $form .= Html::dropDownList($name, $selectedValue, $options, [
            'class' => 'form-select form-select-sm',
            'onchange' => 'this.form.submit()',
        ]);
        $form .= Html::endForm();
        return $form;
    }
}