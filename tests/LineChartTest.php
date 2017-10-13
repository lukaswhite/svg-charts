<?php 

/**
*  Tests the LineChart class
*
*  @author Lukas White <hello@lukaswhite.com>
*/
class LineChartTest extends PHPUnit_Framework_TestCase{
	

    public function testIsThereAnySyntaxError( )
    {
        $chart = $this->getInstance( );
        $this->assertTrue( is_object( $chart ) );
        unset( $chart );
    }

    public function testProducesValidSvg( )
    {
        $chart = $this->getInstance( );
        $rendered = $chart->render( );
        $this->assertInternalType( 'string', $rendered );
        $xml = simplexml_load_string( $rendered );
        $this->assertInstanceOf( \SimpleXMLElement::class, $xml );
        $this->assertEquals( 'svg', $xml->getName( ) );
        $namespaces = $xml->getNamespaces( );
        $this->assertEquals( 'http://www.w3.org/2000/svg', $namespaces[ array_keys( $namespaces )[ 0 ] ] );
    }

    public function testIncludeDimensions( )
    {
        $chart = $this->getInstance( );
        $chart->setWidth( 600 )->setHeight( 300 );
        $chart->includeDimensions( );
        $xml = simplexml_load_string( $chart->render( ) );
        $this->assertTrue( isset( $xml->attributes( )->width ) );
        $this->assertEquals( 600, ( int ) $xml->attributes( )->width );
        $this->assertTrue( isset( $xml->attributes( )->height ) );
        $this->assertEquals( 300, ( int ) $xml->attributes( )->height );

    }

    public function testExcludeDimensions( )
    {
        $chart = $this->getInstance( );
        $chart->setWidth( 600 )->setHeight( 300 );
        $chart->excludeDimensions( );
        $xml = simplexml_load_string( $chart->render( ) );
        $this->assertFalse( isset( $xml->attributes( )->width ) );
        $this->assertFalse( isset( $xml->attributes( )->height ) );

    }

    private function getInstance( )
    {
        return new \Lukaswhite\SvgCharts\LineChart(
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
    }
   
}