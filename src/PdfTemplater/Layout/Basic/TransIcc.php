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
    protected const CMD_NAME = 'transicc';
    protected const CMD_ARGS_RGB_TO_CMYK = ['-i', 'INPUT', '-o', 'OUTPUT', '-n'];
    protected const CMD_ARGS_CMYK_TO_RGB = ['-i', 'INPUT', '-o', 'OUTPUT', '-n'];

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
    private array $cacheRgbToCmyk = [];

    /**
     * @var array[]
     */
    private array $cacheCmykToRgb = [];

    /**
     * @var bool|null
     */
    private ?bool $cacheAvailable = null;

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
        } elseif ($this->cacheAvailable === null) {
            $exitCode = null;
            $output   = null;

            if (\PHP_OS_FAMILY === 'Windows') {
                \exec('where /Q ' . static::CMD_NAME, $output, $exitCode);
            } else {
                \exec('which ' . static::CMD_NAME, $output, $exitCode);
            }

            $this->cacheAvailable = ($exitCode === 0);
        }

        return $this->cacheAvailable;
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
        $this->cacheCmykToRgb = [];
        $this->cacheRgbToCmyk = [];
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
        if ($this->isEnabled() === null) {
            throw new ColorConverterRuntimeException('Converter is not available');
        } elseif ($this->isEnabled() === false) {
            throw new ColorConverterException('Converter is disabled');
        }

        $key = \sprintf('%F|%F|%F', $r, $g, $b);

        if (isset($this->cacheRgbToCmyk[$key])) {
            return $this->cacheRgbToCmyk[$key];
        }

        $cmd = [static::CMD_NAME];
        foreach (static::CMD_ARGS_RGB_TO_CMYK as $arg) {
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

        $ph = \proc_open($cmd, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes, __DIR__, [], ['bypass_shell' => true]);

        if ($ph && $pipes[0] && $pipes[1]) {
            if (\fwrite($pipes[0], \sprintf("%F %F %F", $r * 255, $g * 255, $b * 255)) !== false) {
                \fclose($pipes[0]);
                if (($output = \fgets($pipes[1], 35)) !== false) {
                    \fclose($pipes[1]);
                    \fclose($pipes[2]);
                    \proc_close($ph);

                    $cmyk = \array_map('\floatval', \explode(' ', \trim($output)));

                    if (\count($cmyk) === 4) {
                        $cmyk[0] /= 100;
                        $cmyk[1] /= 100;
                        $cmyk[2] /= 100;
                        $cmyk[3] /= 100;

                        $this->cacheRgbToCmyk[$key] = $cmyk;

                        return $cmyk;
                    }
                } else {
                    \fclose($pipes[0]);
                    \fclose($pipes[1]);
                    \fclose($pipes[2]);
                    \proc_close($ph);

                    throw new ColorConverterRuntimeException('Error performing color conversion: bad output');
                }
            } else {
                \fclose($pipes[0]);
                \fclose($pipes[1]);
                \fclose($pipes[2]);
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
        if ($this->isEnabled() === null) {
            throw new ColorConverterRuntimeException('Converter is not available');
        } elseif ($this->isEnabled() === false) {
            throw new ColorConverterException('Converter is disabled');
        }

        $key = \sprintf('%F|%F|%F|%F', $c, $m, $y, $k);

        if (isset($this->cacheCmykToRgb[$key])) {
            return $this->cacheCmykToRgb[$key];
        }

        $cmd = [static::CMD_NAME];
        foreach (static::CMD_ARGS_CMYK_TO_RGB as $arg) {
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

        $ph = \proc_open($cmd, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes, __DIR__, [], ['bypass_shell' => true]);

        if ($ph && $pipes[0] && $pipes[1]) {
            if (\fwrite($pipes[0], \sprintf("%F %F %F %F ", $c * 100, $m * 100, $y * 100, $k * 100)) !== false) {
                \fclose($pipes[0]);
                if (($output = \fgets($pipes[1], 35)) !== false) {
                    \fclose($pipes[1]);
                    \fclose($pipes[2]);
                    \proc_close($ph);

                    $rgb = \array_map('\floatval', \explode(' ', \trim($output)));

                    if (\count($rgb) === 3) {
                        $rgb[0] /= 255;
                        $rgb[1] /= 255;
                        $rgb[2] /= 255;

                        $this->cacheCmykToRgb[$key] = $rgb;

                        return $rgb;
                    }
                } else {
                    \fclose($pipes[1]);
                    \fclose($pipes[2]);
                    \proc_close($ph);

                    throw new ColorConverterRuntimeException('Error performing color conversion: bad output');
                }
            } else {
                \fclose($pipes[0]);
                \fclose($pipes[1]);
                \fclose($pipes[2]);
                \proc_close($ph);

                throw new ColorConverterRuntimeException('Error performing color conversion: bad input');
            }
        }

        throw new ColorConverterRuntimeException('Error performing color conversion');
    }
}