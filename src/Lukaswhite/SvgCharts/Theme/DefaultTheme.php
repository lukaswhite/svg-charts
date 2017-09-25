<?php namespace Lukaswhite\SvgCharts\Theme;

use Lukaswhite\SvgCharts\Theme\BaseTheme;

class DefaultTheme extends BaseTheme
{
    /**
     * The theme colors
     *
     * @var array
     */
    public $colors = [
        '#396AB1',
        '#DA7C30',
        '#3E9651',
        '#CC2529',
        '#535154',
        '#6B4C9A',
        '#922428',
        '#948B3D',
    ];

    /**
     * The color of the axes
     *
     * @var string
     */
    public $axisColor = '#4a4a4c';

    /**
     * The color of the grid
     *
     * @var string
     */
    public $gridColor = '#9c9c9b';

    /**
     * The font to use for labels
     * @var string
     */
    public $fontFamily = 'sans-serif';

    /**
     * The font size for the X axis, if applicable
     *
     * @var string
     */
    public $xAxisFontSize = '20pt';

    /**
     * The font size for the Y axis, if applicable
     *
     * @var string
     */
    public $yAxisFontSize = '20pt';

    /**
     * The axis width, if applicable
     *
     * @var string
     */
    public $axisWidth;

    /**
     * The stroke width
     *
     * @var integer
     */
    public $strokeWidth = 2;

    /**
     * The grid width, if applicable
     *
     * @var string
     */
    public $gridWidth = 1;

    /**
     * The grid shading background color, if applicable
     *
     * NOTE; if you don't want any shading, set this to NULL
     *
     * @var string|null
     */
    public $gridShadingColor = '#f9f9f9';

}