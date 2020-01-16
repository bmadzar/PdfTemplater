<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\EllipseElement as EllipseElementInterface;
use PdfTemplater\Layout\LayoutArgumentException;

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
    private ?Color $stroke;

    /**
     * @var null|float
     */
    private ?float $strokeWidth;

    /**
     * @var null|Color
     */
    private ?Color $fill;

    /**
     * EllipseElement constructor.
     *
     * @param string     $id
     * @param float      $left
     * @param float      $top
     * @param float      $width
     * @param float      $height
     * @param Color|null $stroke
     * @param float|null $strokeWidth
     * @param Color|null $fill
     */
    public function __construct(string $id, float $left, float $top, float $width, float $height, ?Color $stroke, ?float $strokeWidth, ?Color $fill)
    {
        parent::__construct($id, $left, $top, $width, $height);

        $this->setStroke($stroke);
        $this->setStrokeWidth($strokeWidth);
        $this->setFill($fill);
    }

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
        return ($this->stroke === null ? null : clone $this->stroke);
    }

    /**
     * Sets the stroke width.
     *
     * @param float|null $width
     */
    public function setStrokeWidth(?float $width): void
    {
        if ($width !== null && $width < 0.0) {
            throw new LayoutArgumentException('Stroke width cannot be less than 0!');
        }

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
        return ($this->fill === null ? null : clone $this->fill);
    }
}