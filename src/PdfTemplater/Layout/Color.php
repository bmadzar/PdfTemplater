<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface Color
 *
 * Any value object representing a color must implement this interface.
 *
 * @package PdfTemplater\Layout
 */
interface Color
{
    /**
     * Gets the red component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getRed(float $min = 0, float $max = 1): float;

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getGreen(float $min = 0, float $max = 1): float;

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getBlue(float $min = 0, float $max = 1): float;

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getAlpha(float $min = 0, float $max = 1): float;

    /**
     * Gets the hue component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getHue(float $min = 0, float $max = 1): float;

    /**
     * Gets the saturation component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getSaturation(float $min = 0, float $max = 1): float;

    /**
     * Gets the lightness/brightness component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getLightness(float $min = 0, float $max = 1): float;

    /**
     * Gets the color as a hex string.
     *
     * @return string
     */
    public function getHex(): string;

    /**
     * Gets the color as a hex string, represented as an integer.
     *
     * @return int
     */
    public function getHexAsInt(): int;

    /**
     * Gets the RGB color as a three-element array.
     *
     * @return array
     */
    public function getRgb(): array;

    /**
     * Gets the HSL color as a three-element array.
     *
     * @return array
     */
    public function getHsl(): array;
}