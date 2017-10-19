<?php
require_once('vendor/autoload.php');

setlocale(LC_MONETARY, 'en_GB');



$chart = new \Lukaswhite\SvgCharts\LineChart([
    'labels' => [
        'Oct 2016',
        'Nov 2016',
        'Dec 2016',
        'Jan 2017',
        'Feb 2017',
        'Mar 2017',
        'Apr 2017',
        'May 2017',
        'Jun 2017',
        'Jul 2017',
        'Aug 2017',
        'Sep 2017',
    ],
    'data' => [
        [100000,100120,100230,100340,100450,100680,100830,100920,101040,101300,102400,103000],
        [100100,100240,100367,100488,100530,100610,100500,101200,101400,101800,102200,103400],

    ]
],[
    /**
    'viewsPath' => __DIR__ . '/src/templates',
    'cachePath' => __DIR__ . '/cache',
     **/
    //'colors' => ['#32638e','#f00000'],                  // Colors for datasets
    'strokeWidth' => 4,
    'axisColor' => '#4a4a4c',
    'axisWidth' => 2,
    'gridColor' => '#dddddd',
    'gridWidth' => 1,
    'xAxisFontSize' => '10pt',
    'yAxisFontSize' => '12pt',
    'valueGroups' => 10,
    'start' => 70000,
    'offset' => 1000,
    'valueFormatter' => function($value){               // Closure for formatting values
        //return $value;            // Used setlocale(LC_MONETARY, 'en_US.UTF-8') for this example
        //return money_format("%.0n", $value);
        //return $value;
        return sprintf( '&pound;%s', number_format( money_format('%.0n', $value ), 0 ) );
    }
]);

$chart->setWidth( 1200 )
    ->setHeight( 420 );
    //->excludeDimensions( );

$chart->getTheme()->xAxisFontSize = '8pt';

$svg = $chart->render();

file_put_contents( __DIR__ . '/example.html', $svg );
exit();

$pieChart = new \Lukaswhite\SvgCharts\PieChart([
    'labels'    =>  [
        'one',
        'two',
        'three',
    ],
    'data' => [
        100,
        240,
        200
    ]
], [ ]);

$pieChart->setWidth( 400 )
    ->setHeight( 400 );

$svg = $pieChart->render();

file_put_contents( __DIR__ . '/example.html', $svg );