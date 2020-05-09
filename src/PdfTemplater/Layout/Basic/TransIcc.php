<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\ColorConverterException;

class TransIcc implements ColorConverter
{
    private const CMD_NAME = 'transicc';

    /**
     * @var string|null
     */
    private ?string $cmykProfile = null;

    /**
     * @var string|null
     */
    private ?string $rgbProfile = null;

    /**
     * @var bool
     */
    private bool $enabled = true;

    /**
     * TransIcc constructor.
     *
     * @param string $rgbProfile ICC profile file to use for RGB.
     * @param string $cmykProfile ICC profile file to use for CMYK.
     */
    public function __construct(string $rgbProfile, string $cmykProfile)
    {
        $this->setColorProfiles($rgbProfile, $cmykProfile);
    }

    /**
     * Checks if LittleCMS/TransICC is available on the system.
     *
     * @return bool
     */
    private function isAvailable(): bool
    {
        if (!$this->rgbProfile || !$this->cmykProfile) {
            return false;
        } else {
            $return_var = null;
            $output     = null;
            \exec('which ' . self::CMD_NAME, $output, $return_var);

            return $return_var === 0;
        }
    }

    /**
     * Sets the ColorConverter to enabled or disabled.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Returns true if the converter is enabled, false if it is disabled, and null if it is not available.
     *
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        return $this->isAvailable() ? $this->enabled : null;
    }

    /**
     * Sets the ICC color profiles to use for conversion.
     *
     * @param string $rgbProfile
     * @param string $cmykProfile
     * @throws ColorConverterException If the profile files cannot be read.
     */
    public function setColorProfiles(string $rgbProfile, string $cmykProfile): void
    {
        $rgbProfile  = \realpath($rgbProfile);
        $cmykProfile = \realpath($cmykProfile);

        if ($rgbProfile && \is_file($rgbProfile) && \is_readable($rgbProfile)) {
            $this->rgbProfile = $rgbProfile;
        } else {
            throw new ColorConverterException('Could not read profile file.');
        }

        if ($cmykProfile && \is_file($cmykProfile) && \is_readable($cmykProfile)) {
            $this->cmykProfile = $cmykProfile;
        } else {
            throw new ColorConverterException('Could not read profile file.');
        }
    }

    /**
     * Converts the supplied RGB values (0.0-1.0) to CMYK (0.0-1.0).
     *
     * @param float $r
     * @param float $g
     * @param float $b
     * @return float[] The CMYK values as a 4-element array.
     */
    public function rgbToCmyk(float $r, float $g, float $b): array
    {
        if (!$this->isEnabled()) {
            throw new ColorConverterException('Converter is disabled');
        }
    }

    /**
     * Converts the supplied CMYK values (0.0-1.0) to RGB (0.0-1.0).
     *
     * @param float $c
     * @param float $m
     * @param float $y
     * @param float $k
     * @return float[]
     */
    public function cmykToRgb(float $c, float $m, float $y, float $k): array
    {
        if (!$this->isEnabled()) {
            throw new ColorConverterException('Converter is disabled');
        }
    }
}