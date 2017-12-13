<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface EllipseElement
 *
 * An ellipse, the dimensions of which are represented by the height and width of the Element.
 * Ellipses can have a border and fill.
 *
 * @package PdfTemplater\Layout
 */
interface EllipseElement extends Element
{
    /**
     * Sets the stroke color.
     *
     * @param null|Color $color
     */
    public function setStroke(?Color $color): void;

    /**
     * Gets the stroke color.
     *
     * @return null|Color
     */
    public function getStroke(): ?Color;

    /**
     * Sets the stroke width.
     *
     * @param float|null $width
     */
    public function setStrokeWidth(?float $width): void;

    /**
     * Gets the stroke width.
     *
     * @return float|null
     */
    public function getStrokeWidth(): ?float;

    /**
     * Sets the fill color.
     *
     * @param null|Color $color
     */
    public function setFill(?Color $color): void;

    /**
     * Gets the fill color.
     *
     * @return null|Color
     */
    public function getFill(): ?Color;
}