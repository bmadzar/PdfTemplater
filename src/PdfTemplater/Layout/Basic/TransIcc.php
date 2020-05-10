<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\ColorConverterException;
use PdfTemplater\Layout\ColorConverterRuntimeException;

/**
 * Color converter class for LittleCMS/transicc.
 *
 * @package PdfTemplater\Layout\Basic
 */
class TransIcc implements ColorConverter
{
    private const CMD_NAME = 'transicc';
    private const CMD_ARGS_RGB_TO_CMYK = ['-i', 'INPUT', '-o', 'OUTPUT'];
    private const CMD_ARGS_CMYK_TO_RGB = ['-i', 'INPUT', '-o', 'OUTPUT'];

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
     * @var array[]
     */
    private array $cache_rgb_to_cmyk = [];

    /**
     * @var array[]
     */
    private array $cache_cmyk_to_rgb = [];

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

            if (\PHP_OS_FAMILY === 'Windows') {
                \exec('where /Q ' . self::CMD_NAME, $output, $return_var);
            } else {
                \exec('which ' . self::CMD_NAME, $output, $return_var);
            }

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
     * @throws ColorConverterRuntimeException If the profile files cannot be read.
     */
    public function setColorProfiles(string $rgbProfile, string $cmykProfile): void
    {
        $rgbProfile  = \realpath($rgbProfile);
        $cmykProfile = \realpath($cmykProfile);

        if ($rgbProfile && \is_file($rgbProfile) && \is_readable($rgbProfile)) {
            $this->rgbProfile = $rgbProfile;
        } else {
            throw new ColorConverterRuntimeException('Could not read profile file.');
        }

        if ($cmykProfile && \is_file($cmykProfile) && \is_readable($cmykProfile)) {
            $this->cmykProfile = $cmykProfile;
        } else {
            throw new ColorConverterRuntimeException('Could not read profile file.');
        }

        // Clear caches
        $this->cache_cmyk_to_rgb = [];
        $this->cache_rgb_to_cmyk = [];
    }

    /**
     * Returns the ICC color profiles used, as a 2-element array.
     *
     * @return string[]
     */
    public function getColorProfiles(): array
    {
        return [
            'rgb'  => $this->rgbProfile,
            'cmyk' => $this->cmykProfile,
        ];
    }

    /**
     * Converts the supplied RGB values (0.0-1.0) to CMYK (0.0-1.0).
     *
     * @param float $r
     * @param float $g
     * @param float $b
     * @return float[] The CMYK values as a 4-element array.
     * @throws ColorConverterException Thrown if converter is disabled.
     * @throws ColorConverterRuntimeException Thrown if an error occurs during conversion.
     */
    public function rgbToCmyk(float $r, float $g, float $b): array
    {
        if (!$this->isEnabled()) {
            throw new ColorConverterException('Converter is disabled');
        }

        $key = \sprintf('%F|%F|%F', $r, $g, $b);

        if (isset($this->cache_rgb_to_cmyk[$key])) {
            return $this->cache_rgb_to_cmyk[$key];
        }

        $cmd = [self::CMD_NAME];
        foreach (self::CMD_ARGS_RGB_TO_CMYK as $arg) {
            if ($arg === 'INPUT') {
                $cmd[] = $this->rgbProfile;
            } elseif ($arg === 'OUTPUT') {
                $cmd[] = $this->cmykProfile;
            } else {
                $cmd[] = $arg;
            }
        }
        unset($arg);

        $pipes = [];

        $ph = \proc_open($cmd, [['pipe', 'r'], ['pipe', 'w']], $pipes, __DIR__, [], ['bypass_shell' => true]);

        if ($ph && $pipes[0] && $pipes[1]) {
            if (\fwrite($pipes[0], \sprintf("%F\n%F\n%F\n", $r, $g, $b)) !== false) {
                if (($output = \fgets($pipes[1])) !== false) {
                    \proc_close($ph);

                    $cmyk = \array_map('floatval', \explode(' ', \trim($output)));

                    if (\count($cmyk) === 4) {
                        $this->cache_rgb_to_cmyk[$key] = $cmyk;

                        return $cmyk;
                    }
                } else {
                    \proc_close($ph);

                    throw new ColorConverterRuntimeException('Error performing color conversion: bad output');
                }
            } else {
                \proc_close($ph);

                throw new ColorConverterRuntimeException('Error performing color conversion: bad input');
            }
        }

        throw new ColorConverterRuntimeException('Error performing color conversion');
    }

    /**
     * Converts the supplied CMYK values (0.0-1.0) to RGB (0.0-1.0).
     *
     * @param float $c
     * @param float $m
     * @param float $y
     * @param float $k
     * @return float[]
     * @throws ColorConverterException Thrown if converter is disabled.
     * @throws ColorConverterRuntimeException Thrown if an error occurs during conversion.
     */
    public function cmykToRgb(float $c, float $m, float $y, float $k): array
    {
        if (!$this->isEnabled()) {
            throw new ColorConverterException('Converter is disabled');
        }

        $key = \sprintf('%F|%F|%F|%F', $c, $m, $y, $k);

        if (isset($this->cache_cmyk_to_rgb[$key])) {
            return $this->cache_cmyk_to_rgb[$key];
        }

        $cmd = [self::CMD_NAME];
        foreach (self::CMD_ARGS_CMYK_TO_RGB as $arg) {
            if ($arg === 'INPUT') {
                $cmd[] = $this->cmykProfile;
            } elseif ($arg === 'OUTPUT') {
                $cmd[] = $this->rgbProfile;
            } else {
                $cmd[] = $arg;
            }
        }
        unset($arg);


        $pipes = [];

        $ph = \proc_open($cmd, [['pipe', 'r'], ['pipe', 'w']], $pipes, __DIR__, [], ['bypass_shell' => true]);

        if ($ph && $pipes[0] && $pipes[1]) {
            if (\fwrite($pipes[0], \sprintf("%F\n%F\n%F\n%F\n", $c, $m, $y, $k)) !== false) {
                if (($output = \fgets($pipes[1])) !== false) {
                    \proc_close($ph);

                    $rgb = \array_map('floatval', \explode(' ', \trim($output)));

                    if (\count($rgb) === 3) {
                        $this->cache_cmyk_to_rgb[$key] = $rgb;

                        return $rgb;
                    }
                } else {
                    \proc_close($ph);

                    throw new ColorConverterRuntimeException('Error performing color conversion: bad output');
                }
            } else {
                \proc_close($ph);

                throw new ColorConverterRuntimeException('Error performing color conversion: bad input');
            }
        }

        throw new ColorConverterRuntimeException('Error performing color conversion');
    }
}