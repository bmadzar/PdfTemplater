<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\LineElement as LineElementInterface;

/**
 * Class LineElement
 *
 * A basic implementation of a Line Element.
 *
 * @package PdfTemplater\Layout\Basic
 */
class LineElement extends Element implements LineElementInterface
{

    /**
     * @var float
     */
    private $lineWidth;

    /**
     * @var Color
     */
    private $lineColor;

    /**
     * Sets the thickness of the line.
     *
     * @param float $width
     */
    public function setLineWidth(float $width): void
    {
        if ($width < 0.00) {
            throw new LayoutArgumentException('Line width must be 0 or greater!');
        }

        $this->lineWidth = $width;
    }

    /**
     * Gets the thickness of the line.
     *
     * @return float
     */
    public function getLineWidth(): float
    {
        if ($this->lineWidth === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->lineWidth;
    }

    /**
     * Sets the color of the line.
     *
     * @param Color $color
     */
    public function setLineColor(Color $color): void
    {
        $this->lineColor = $color;
    }

    /**
     * Gets the color of the line.
     *
     * @return Color
     */
    public function getLineColor(): Color
    {
        if ($this->lineColor === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->lineColor;
    }
}