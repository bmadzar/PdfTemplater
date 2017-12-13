<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\EllipseElement as EllipseElementInterface;

/**
 * Class EllipseElement
 *
 * Basic implementation of Ellipse Element.
 *
 * @package PdfTemplater\Layout\Basic
 */
class EllipseElement extends Element implements EllipseElementInterface
{
    /**
     * @var null|Color
     */
    private $stroke;

    /**
     * @var null|float
     */
    private $strokeWidth;

    /**
     * @var null|Color
     */
    private $fill;

    /**
     * Sets the stroke color.
     *
     * @param null|Color $color
     */
    public function setStroke(?Color $color): void
    {
        $this->stroke = $color;
    }

    /**
     * Gets the stroke color.
     *
     * @return null|Color
     */
    public function getStroke(): ?Color
    {
        return $this->stroke;
    }

    /**
     * Sets the stroke width.
     *
     * @param float|null $width
     */
    public function setStrokeWidth(?float $width): void
    {
        $this->strokeWidth = $width;
    }

    /**
     * Gets the stroke width.
     *
     * @return float|null
     */
    public function getStrokeWidth(): ?float
    {
        return $this->strokeWidth;
    }

    /**
     * Sets the fill color.
     *
     * @param null|Color $color
     */
    public function setFill(?Color $color): void
    {
        $this->fill = $color;
    }

    /**
     * Gets the fill color.
     *
     * @return null|Color
     */
    public function getFill(): ?Color
    {
        return $this->fill;
    }
}