<?php namespace Lukaswhite\SvgCharts;

use SVG\Nodes\Shapes\SVGRect;
use SVG\SVGImage;
use SVG\Nodes\Shapes\SVGLine;
use SVG\Nodes\Shapes\SVGPath;
use SVG\Nodes\Shapes\SVGPolyline;
use SVG\Nodes\Shapes\SVGPolygon;
use SVG\Nodes\Shapes\SVGText;

class LineChart extends Chart
{

    /**
     * This simply defines the default options
     *
     * @var array
     */
    protected $options = [
        'mode'              =>  'lines',
        'valueGroups'       =>  5,
        'margin'            =>  10,
        'offset'            =>  0,
        'yAxisLeftPosition' =>  10, // i.e. the Y axis starts 10% from the left
        'labelRotation'     =>  0,
    ];

    /**
     * The minimum value
     *
     * @var int|null
     */
    protected $min = null;

    /**
     * The maximum value
     *
     * @var int|null
     */
    protected $max = null;


    /**
     * LineChart constructor.
     *
     * @param $data
     * @param null $options
     */
    public function __construct( $data, $options )
    {
        parent::__construct( $data, $options );

        $this->min = PHP_INT_MAX;
        $this->max = -PHP_INT_MAX;

        // If required, adjust the values, to take into account the start value (i.e. where the Y axis starts,
        // if it's not to start at zero)
        if ( isset( $this->options[ 'start' ] ) ) {
            for ( $i = 0; $i < count( $this->data[ 'data' ] ); $i++ ) {
                $start = $this->options[ 'start' ];
                array_walk( $this->data[ 'data' ][ $i ], function ( &$value ) use ( $start ) {
                    $value -= $start;
                } );
            }
        }

        // Calculate the minimum and maximum values
        for ($i = 0; $i < count($this->data['data']); $i++) {
            foreach ($this->data['data'][$i] as $val) {
                $this->min = min($this->min, $val);
                $this->max = max($this->max, $val);
            }
        }

        if ($this->min < $this->max) {
            $exp = floor(log($this->max, 10));
            $base = pow(10, $exp - 1);

            $this->max = ceil($this->max / $base) * $base;
            $this->min = floor(( $this->min - $this->options[ 'offset' ] ) / $base) * $base;
        } else {
            $this->min = 0;
            $this->max = 0;

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

        /**
        if ( ! $this->includeDimensionsInSVG ) {
            $image->getDocument( )->setHeight( null );
            $image->getDocument( )->setWidth( null );
        }
         **/

        $doc = $image->getDocument( );

        // Grid lines
        $delta = 1;

        $grid = $this->grid( );

        foreach( $grid['values'] as $y => $val)
        {

            $line = new SVGLine(
                $this->options[ 'margin' ],
                $y,
                $this->axisX1,
                $y
            );

            $line->setStyle( 'stroke', $this->theme->gridColor );
            $line->setStyle( 'stroke-width', $this->theme->gridWidth );

            $doc->addChild( $line );

            if ( $this->theme->gridShadingColor && ( $delta % 2 != 0 ) ) {
                $shading = new SVGRect(
                    $this->options[ 'margin' ],
                    $y,
                    ( $this->axisX1 - $this->options[ 'margin' ] ),
                    ( array_keys( $grid[ 'values' ] )[ 0 ] - array_keys( $grid[ 'values' ] )[ 1 ] )
                );
                $shading->setAttribute( 'fill', $this->theme->gridShadingColor );
                $doc->addChild( $shading );
            }


            $label = new SVGText(
                $val,
                $this->options[ 'margin' ],
                ( $y - $this->height * 0.01 )
            );

            $label->setStyle( 'font-family', $this->theme->fontFamily );
            $label->setStyle( 'font-size', $this->theme->yAxisFontSize  );
            $label->setAttribute( 'fill', $this->theme->axisColor );

            $doc->addChild( $label );

            $delta++;

        }

        // Labels
        foreach( $this->grid( )['labels'] as $x => $label )
        {
            $line = new SVGLine(
                $x,
                ( $this->height * .9 - $this->options[ 'margin' ] ),
                $x,
                ( $this->height * .91 - $this->options[ 'margin' ] )
            );

            $line->setStyle( 'stroke', $this->theme->axisColor );
            $line->setStyle( 'stroke-width', $this->theme->axisWidth );

            $doc->addChild( $line );

            $text = new SVGText(
                $label,
                $x,
                ( $this->height * .93 )
            );

            $text->setStyle( 'font-family', $this->theme->fontFamily );
            $text->setStyle( 'font-size', $this->theme->xAxisFontSize );
            $text->setAttribute( 'fill', $this->theme->axisColor );

            // Optionally apply the rotation
            if ( $this->options[ 'labelRotation' ] > 0 ) {
                $text->setAttribute( 'transform', sprintf( 'rotate(%d,%d,%d)', $this->options[ 'labelRotation' ], $x, ( $this->height * .93 ) ) );
                $text->setStyle( 'text-anchor', 'start' );
            }

            $doc->addChild( $text );


        }

        $xAxis = new SVGLine(
            $this->axisX0,
            $this->axisY0,
            $this->axisX1,
            $this->axisY0
        );

        $xAxis->setStyle( 'stroke', $this->theme->axisColor );
        $xAxis->setStyle( 'stroke-width', $this->theme->axisWidth );

        $doc->addChild( $xAxis );

        $yAxis = new SVGLine(
            $this->axisX0,
            $this->axisY0,
            $this->axisX0,
            $this->axisY1
        );

        $yAxis->setStyle( 'stroke', $this->theme->axisColor );
        $yAxis->setStyle( 'stroke-width', $this->theme->axisWidth );

        $doc->addChild( $yAxis );

        if ( $this->options[ 'mode' ] == 'lines' ) {
            foreach( $this->getLines( ) as $i => $points ) {
                $line = new SVGPolyline( $points );
                $line->setStyle( 'stroke', $this->theme->colors[ $i ] );
                $line->setStyle( 'stroke-width', $this->theme->strokeWidth );
                $line->setAttribute( 'fill', 'none' );
                $doc->addChild( $line );
            }
        }

        return $image->toXMLString( );

    }

    /**
     * Determine whether the chart is empty; i.e. it has no data
     * @return bool
     */
    protected function isEmpty()
    {
        foreach ($this->data['data'] as $data) {
            if (!empty($data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the dimensions
     *
     * @return array
     */
    protected function dimensions()
    {
        return [
            'axisX0' => $this->axisX0,
            'axisY0' => $this->axisY0,
            'axisX1' => $this->axisX1,
            'axisY1' => $this->axisY1
        ];
    }

    /**
     * Build the grid
     *
     * @return array
     */
    protected function grid()
    {
        $res = [
            'values' => [],
            'labels' => []
        ];


        $step = ceil(count($this->data['labels']) / 10);
        $step = 1;

        $i = 0;
        $wth = ($this->width * .9 - 2 * $this->margin) / count($this->data['labels']);
        $x = $this->margin + ($this->width * ( ( $this->options[ 'yAxisLeftPosition' ] / 100 ) ) );

        foreach ($this->data['labels'] as $ts => $label) {
            if (0 === $i++ % $step) {

                $res['labels'][$x] = $this->data['labels'][$ts];
                $x += $wth * $step;

            }
        }


        for ($i = 1; $i < $this->valueGroups; $i++) {
            $y = $this->height * .9 - $this->margin - ($i / $this->valueGroups) * ($this->height * .9 - 2 * $this->margin);
            $res['values'][$y] = $this->min + $i * ($this->max - $this->min) / $this->valueGroups;
            if ( isset( $this->options[ 'start' ] ) ) {
                $res[ 'values' ][ $y ] += $this->options[ 'start' ];
            }

            if ( isset( $this->options[ 'valueFormatter' ] ) ) {
                $res['values'][$y] = $this->options['valueFormatter']($res['values'][$y]);
            }
        }


        return $res;
    }

    /**
     * Construct the paths
     *
     * @return array
     */
    protected function paths()
    {
        $res = [];

        $wth = $this->width * .9 - 2 * $this->margin;
        $hth = $this->height * .9 - 2 * $this->margin;

        foreach ($this->data['data'] as $data) {

            $c = count($data);

            $stepX = $wth / $c;

            $x = $this->axisX0 - $stepX;


            $path = "M" . $this->axisX0 . " " . $this->axisY0;

            foreach ($data as $value) {
                $y = $this->axisY0 - ($value - $this->min) / ($this->max - $this->min) * $hth;

                $x += $stepX;
                $path .= " L" . $x . " " . $y;


            }

            $path .= " L" . $x . " " . $this->axisY0;


            $res[] = $path;
        }


        return $res;
    }

    /**
     * Construct the lines
     *
     * @return array
     *
     * @todo Return an array of points instead of a string
     */
    protected function lines( )
    {
        $res = [];

        $wth = $this->width * .9 - 2 * $this->margin;
        $hth = $this->height * .9 - 2 * $this->margin;

        foreach ($this->data['data'] as $data) {

            $c = count($data);

            $stepX = $wth / $c;

            $x = $this->axisX0 - $stepX;


            //$path = $this->axisX0 . "," . $this->axisY0;
            $path = '';

            $points = [ ];

            foreach ($data as $value) {
                $y = $this->axisY0 - ($value - $this->min) / ($this->max - $this->min) * $hth;

                $x += $stepX;
                $path .= " " . $x . "," . $y;

                $points = [ $x, $y ];

            }

            //$path .= " L" . $x . " " . $this->axisY0;


            $res[] = $path;


        }


        return $res;
    }

    /**
     * Construct the lines
     *
     * @return array
     */
    protected function getLines( )
    {
        $wth = $this->width * .9 - 2 * $this->margin;
        $hth = $this->height * .9 - 2 * $this->margin;

        $lines = [ ];

        foreach ($this->data[ 'data' ] as $data) {

            $c = count($data);
            $stepX = $wth / $c;
            $x = $this->axisX0 - $stepX;

            $points = [ ];

            foreach ($data as $value) {
                $y = $this->axisY0 - ($value - $this->min) / ($this->max - $this->min) * $hth;
                $x += $stepX;
                $points[ ] = [ $x, $y ];
            }

            $lines[ ] = $points;

        }


        return $lines;
    }

    /**
     * Magic GETters
     *
     * @param string $name
     * @return mixed
     *
     * @todo Either have these as attributes and calculate them before rendering, or make them methods.
     */
    public function __get( $name )
    {
        switch ( $name ) {
            case 'axisX0':
                return $this->margin + $this->width * ( $this->options[ 'yAxisLeftPosition' ] / 100 );
                break;
            case 'axisY0':
                return $this->height * 0.9 - $this->margin;
                break;
            case 'axisX1':
                return $this->width - $this->margin;
                break;
            case 'axisY1':
                return $this->margin;
                break;
            default:
                return $this->options[$name];
        }


    }


}