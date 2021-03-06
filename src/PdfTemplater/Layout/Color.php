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
    public function getRed(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getGreen(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getBlue(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getAlpha(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the hue component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getHue(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the saturation component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getSaturation(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the lightness/brightness component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getLightness(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the cyan component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getCyan(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the magenta component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getMagenta(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the yellow component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getYellow(float $min = 0.0, float $max = 1.0): float;

    /**
     * Gets the black component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getBlack(float $min = 0.0, float $max = 1.0): float;

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
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getRgb(float $min = 0.0, float $max = 1.0): array;

    /**
     * Gets the HSL color as a three-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getHsl(float $min = 0.0, float $max = 1.0): array;

    /**
     * Gets the CMYK color as a four-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getCmyk(float $min = 0.0, float $max = 1.0): array;

    /**
     * Combines the supplied background Color with this color
     * taking into account the alpha value.
     *
     * @param Color $background The background color.
     * @return Color The mixed color.
     */
    public function getMixed(Color $background): self;
}