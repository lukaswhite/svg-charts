<?php

namespace SVG\Nodes\Shapes;

use SVG\Nodes\SVGNode;
use SVG\Rasterization\SVGRasterizer;

/**
 * Represents the SVG tag 'line'.
 * Has the special attributes x1, y1, x2, y2.
 */
class SVGText extends SVGNode
{
    const TAG_NAME = 'text';



    /**
     * @param string|null $x1 The first point's x coordinate.
     * @param string|null $y1 The first point's y coordinate.
     * @param string|null $x2 The second point's x coordinate.
     * @param string|null $y2 The second point's y coordinate.
     */
    public function __construct($text, $x = null, $y = null)
    {
        parent::__construct();

        $this->contents = $text;
        $this->setAttributeOptional('x', $x);
        $this->setAttributeOptional('y', $y);
    }



    /**
     * @return string The first point's x coordinate.
     */
    public function getX()
    {
        return $this->getAttribute('x');
    }

    /**
     * Sets the first point's x coordinate.
     *
     * @param string $x1 The new coordinate.
     *
     * @return $this This node instance, for call chaining.
     */
    public function setX($x)
    {
        return $this->setAttribute('x', $x);
    }

    /**
     * @return string The first point's y coordinate.
     */
    public function getY()
    {
        return $this->getAttribute('y');
    }

    /**
     * Sets the first point's y coordinate.
     *
     * @param string $y1 The new coordinate.
     *
     * @return $this This node instance, for call chaining.
     */
    public function setY($y)
    {
        return $this->setAttribute('y', $y);
    }




    public function rasterize(SVGRasterizer $rasterizer)
    {
        if ($this->getComputedStyle('display') === 'none') {
            return;
        }

        $visibility = $this->getComputedStyle('visibility');
        if ($visibility === 'hidden' || $visibility === 'collapse') {
            return;
        }

        $rasterizer->render('text', array(
            'x'    => $this->getX(),
            'y'    => $this->getY(),
        ), $this);
    }
}
