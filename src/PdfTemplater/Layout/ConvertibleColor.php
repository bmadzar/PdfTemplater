<?php

declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface ConvertibleColor
 *
 * Any Color that supports using a ColorConverter must implement this interface.
 *
 * @package PdfTemplater\Layout
 */
interface ConvertibleColor extends Color
{
    /**
     * Sets the ColorConverter used to convert between CMYK and RGB.
     *
     * @param ColorConverter|null $converter
     */
    public function setConverter(?ColorConverter $converter): void;

    /**
     * Gets the ColorConverter used to convert between CMYK and RGB.
     *
     * @return ColorConverter|null
     */
    public function getConverter(): ?ColorConverter;
}