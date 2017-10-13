<?php namespace Lukaswhite\SvgCharts;

use Lukaswhite\SvgCharts\Theme\BaseTheme;
use Lukaswhite\SvgCharts\Theme\DefaultTheme;

abstract class Chart
{
    /**
     * The chart data
     *
     * @var array
     */
    protected $data = null;
    protected $options = [];

    /**
     * The width of the chart
     *
     * @var integer
     */
    protected $width;

    /**
     * The height of the chart
     *
     * @var integer
     */
    protected $height;

    /**
     * The theme
     *
     * @var BaseTheme
     */
    protected $theme;

    /**
     * Whether to include the width and height in the generated SVG
     *
     * @var bool
     */
    protected $includeDimensionsInSVG = true;

    /**
     * Render the chart, as an SVG
     *
     * @return string
     */
    abstract public function render();

    /**
     * Chart constructor.
     *
     * @param $data
     * @param array $options
     */
    public function __construct($data, $options = null)
    {
        $this->data = $data;

        // Set the default theme
        $this->setTheme( new DefaultTheme( ) );

        if (isset($options) && is_array($options)) {
            $this->options = array_merge($this->options, $options);
        }

    }

    /**
     * Set the theme
     *
     * @var BaseTheme $theme
     */
    public function setTheme( BaseTheme $theme )
    {
        $this->theme = $theme;
    }

    /**
     * Include the dimensions
     *
     * @return $this
     */
    public function includeDimensions( )
    {
        $this->includeDimensionsInSVG = true;
        return $this;
    }

    /**
     * Exclude the dimensions
     *
     * @return $this
     */
    public function excludeDimensions( )
    {
        $this->includeDimensionsInSVG = false;
        return $this;
    }

    /**
     * Set an option
     *
     * @param string $name
     * @param mixed $value
     */
    public function setOption( $name, $value )
    {
        $this->options[ $name ] = $value;
    }

    /**
     * Get the ID of the chart SVG or, if not provided, generate one.
     *
     * @return mixed|string
     */
    public function getId( )
    {
        if ( isset( $this->options[ 'id' ] ) ) {
            return $this->options[ 'id' ];
        }
        return uniqid( 'chart_' );
    }

    /**
     * Get the current theme
     *
     * @return BaseTheme
     */
    public function getTheme( )
    {
        return $this->theme;
    }

    /**
     * Set the width
     *
     * @param integer $value
     * @return $this
     */
    public function setWidth( $value )
    {
        $this->width = $value;
        return $this;
    }

    /**
     * Set the height
     *
     * @param integer $value
     * @return $this
     */
    public function setHeight( $value )
    {
        $this->height = $value;
        return $this;
    }


    /**
     * @return string
     */
    public function toBase64()
    {
        return base64_encode($this->render());
    }


    /**
     * @return string
     */
    public function toImgSrc()
    {
        return "data:image/svg+xml;base64," . $this->toBase64();
    }
}