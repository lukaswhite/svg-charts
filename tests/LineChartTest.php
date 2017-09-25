<?php 

/**
*  Tests the LineChart class
*
*  @author Lukas White <hello@lukaswhite.com>
*/
class LineChartTest extends PHPUnit_Framework_TestCase{
	

    public function testIsThereAnySyntaxError( )
    {
        $chart = new \Lukaswhite\SvgCharts\LineChart(
            [
                'labels' => [ 'one', 'two', 'three' ],
                'data' => [
                    [ 1, 2, 3 ],
                    [ 1, 3, 5 ],
                ]
            ],
            [

            ]
        );
        $this->assertTrue( is_object( $chart ) );
        unset( $chart );
    }
   
}