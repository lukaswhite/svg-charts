<?php namespace Lukaswhite\SvgCharts;

use SVG\SVGImage;
use SVG\Nodes\Shapes\SVGPath;

class PieChart extends Chart
{

    /**
     * This simply defines the default options
     *
     * @var array
     */
    protected $options = [

    ];

    /**
     * The percentages (radians), which get calculated shortly
     *
     * @var array
     */
    private $percentages = [ ];

    /**
     * PieChart constructor.
     *
     * @param $data
     * @param null $options
     */
    public function __construct( $data, $options )
    {
        parent::__construct( $data, $options );

        // Total up the values...
        $total = array_sum( $this->data[ 'data' ] );

        // ...then convert them into percentages (radians)
        foreach( $this->data[ 'data' ] as $value ) {
            $this->percentages[ ] = $value / $total;
        }

    }

    /**
     * Render the chart
     *
     * @return string
     */
    public function render( )
    {
        if ( $this->includeDimensionsInSVG ) {
            $image = new SVGImage(
                $this->width,
                $this->height
            );
        } else {
            $image = new SVGImage(
                null,
                null
            );
        }

        $doc = $image->getDocument( );

        $doc->setAttribute( 'viewBox', '-1 -1 2 2' );
        $doc->setStyle( 'transform', 'rotate(-0.25turn)' );

        /**
        $circle = new SVGCircle( 0, 0, 1 );

        $circle->setStyle( 'fill', '#f5f5f5' );


        $doc->addChild( $circle );
         **/

        $cumulativeRadians = 0;

        foreach( $this->percentages as $i => $value ) {

            // sweep flag
            $largeArcFlag = ( $value > 0.5 ) ? 1 : 0;

            // Calculate the starting point
            $startX = cos( $cumulativeRadians );
            $startY = sin( $cumulativeRadians );

            // Move
            $cumulativeRadians += ( 2 * pi( ) * $value );

            // Calculate the end point
            $endX = cos( $cumulativeRadians );
            $endY = sin( $cumulativeRadians );

            // Build the path
            $d = sprintf(
                'M %f %f A 1 1 0 %d 1 %f %f L 0 0',
                $startX,
                $startY,
                $largeArcFlag,
                $endX,
                $endY
            );

            // Create a new slice...
            $slice = new SVGPath( $d );

            // ...set the background color...
            $slice->setStyle( 'fill', $this->theme->colors[ $i ] );

            // ...and add it to the chart
            $doc->addChild( $slice );

        }

        // Render!
        return $image->toXMLString( );

    }

}