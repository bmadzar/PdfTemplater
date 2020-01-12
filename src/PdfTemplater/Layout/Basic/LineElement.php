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
    private float $lineWidth;

    /**
     * @var Color
     */
    private Color $lineColor;

    /**
     * LineElement constructor.
     *
     * @param string $id
     * @param float  $left
     * @param float  $top
     * @param float  $width
     * @param float  $height
     * @param float  $lineWidth
     * @param Color  $lineColor
     */
    public function __construct(string $id, float $left, float $top, float $width, float $height, float $lineWidth, Color $lineColor)
    {
        parent::__construct($id, $left, $top, $width, $height);

        $this->setLineWidth($lineWidth);
        $this->setLineColor($lineColor);
    }

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
        return clone $this->lineColor;
    }
}