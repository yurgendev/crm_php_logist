<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $data */

$this->title = 'Dashboard';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="chart-container">
        <canvas id="logisticsChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('logisticsChart').getContext('2d');
        var logisticsChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($data)) ?>,
                datasets: [{
                    label: 'Количество автомобилей',
                    data: <?= json_encode(array_values($data)) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Отключаем сохранение соотношения сторон
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>

</div>