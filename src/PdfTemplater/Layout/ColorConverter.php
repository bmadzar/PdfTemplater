<?php

declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface ColorConverter
 *
 * Classes for conversion between RGB and CMYK colorspaces must implement this interface.
 *
 * @package PdfTemplater\Layout
 */
interface ColorConverter
{
    /**
     * Returns true if the converter is enabled, false if it is disabled, and null if it is not available.
     *
     * @return bool|null
     */
    public function isEnabled(): ?bool;

    /**
     * Sets the ICC color profiles to use for conversion.
     *
     * @param string $rgbProfile
     * @param string $cmykProfile
     */
    public function setColorProfiles(string $rgbProfile, string $cmykProfile): void;

    /**
     * Returns the ICC color profiles used, as a 2-element array.
     *
     * @return string[]
     */
    public function getColorProfiles(): array;

    /**
     * Converts the supplied RGB values (0.0-1.0) to CMYK (0.0-1.0).
     *
     * @param float $r
     * @param float $g
     * @param float $b
     * @return float[] The CMYK values as a 4-element array.
     */
    public function rgbToCmyk(float $r, float $g, float $b): array;

    /**
     * Converts the supplied CMYK values (0.0-1.0) to RGB (0.0-1.0).
     *
     * @param float $c
     * @param float $m
     * @param float $y
     * @param float $k
     * @return float[]
     */
    public function cmykToRgb(float $c, float $m, float $y, float $k): array;
}