<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface LineElement
 *
 * A basic line.
 *
 * @package PdfTemplater\Layout
 */
interface LineElement extends Element
{
    /**
     * Sets the thickness of the line.
     *
     * @param float $width
     */
    public function setLineWidth(float $width): void;

    /**
     * Gets the thickness of the line.
     *
     * @return float
     */
    public function getLineWidth(): float;

    /**
     * Sets the color of the line.
     *
     * @param Color $color
     */
    public function setLineColor(Color $color): void;

    /**
     * Gets the color of the line.
     *
     * @return Color
     */
    public function getLineColor(): Color;
}