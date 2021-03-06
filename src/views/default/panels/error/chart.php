<?php
/* @var $panel ErrorPanel */

use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\panels\ErrorPanel;
use dosamigos\chartjs\ChartJs;

$days = [];
$count = [];
foreach (range(-6, 0) as $day) {
    $date = strtotime($day . 'days');
    $days[] = date('D: Y-m-d', $date);
    //$count[] = AuditError::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
    $filterDate = Yii::$app->formatter->asDate($date);
    $fromTime = Yii::$app->formatter->asTime('00:00:00'); //from time
    $toTime = Yii::$app->formatter->asTime('23:59:59'); //to time

    $count[] = AuditError::find()
        ->where(['between', 'created',
            "$filterDate $fromTime",
            "$filterDate $toTime",])
        //->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])
        ->count();
}

echo ChartJs::widget([
    'type' => 'bar',
    'clientOptions' => [
        'legend' => ['display' => false],
        'tooltips' => ['enabled' => false],
    ],
    'data' => [
        'labels' => $days,
        'datasets' => [
            [
                'fillColor' => 'rgba(151,187,205,0.5)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => $count,
            ],
        ],
    ]
]);
