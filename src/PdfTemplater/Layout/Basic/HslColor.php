<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\ConvertibleColor;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class HslColor
 *
 * Color class for HSLA values.
 *
 * @package PdfTemplater\Layout\Basic
 */
class HslColor implements ConvertibleColor
{
    /**
     * @var float
     */
    private float $hue;

    /**
     * @var float
     */
    private float $saturation;

    /**
     * @var float
     */
    private float $lightness;

    /**
     * @var float
     */
    private float $alpha;

    /**
     * @var ColorConverter|null
     */
    private ?ColorConverter $converter;

    /**
     * HslColor constructor.
     *
     * @param float               $hue
     * @param float               $saturation
     * @param float               $lightness
     * @param float               $alpha
     * @param ColorConverter|null $converter
     */
    public function __construct(float $hue, float $saturation, float $lightness, float $alpha = 1.0, ?ColorConverter $converter = null)
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setLightness($lightness);
        $this->setAlpha($alpha);
        $this->setConverter($converter);
    }

    /**
     * Sets the lightness value -- between 0 and 1.
     *
     * @param float $lightness
     * @throws LayoutArgumentException If $lightness is out of range
     */
    public function setLightness(float $lightness): void
    {
        if ($lightness < 0.00 || $lightness > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->lightness = $lightness;
    }

    /**
     * Sets the saturation value -- between 0 and 1.
     *
     * @param float $saturation
     * @throws LayoutArgumentException If $saturation is out of range
     */
    public function setSaturation(float $saturation): void
    {
        if ($saturation < 0.00 || $saturation > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->saturation = $saturation;
    }

    /**
     * Sets the hue value -- between 0 and 1.
     *
     * @param float $hue
     * @throws LayoutArgumentException If $hue is out of range
     */
    public function setHue(float $hue): void
    {
        if ($hue < 0.00 || $hue > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->hue = $hue;
    }

    /**
     * Sets the alpha value -- between 0 and 1.
     *
     * @param float $alpha
     * @throws LayoutArgumentException If $alpha is out of range
     */
    public function setAlpha(float $alpha): void
    {
        if ($alpha < 0.00 || $alpha > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->alpha = $alpha;
    }

    /**
     * Gets the red component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getRed(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $c = (1 - \abs((2 * $this->lightness) - 1)) * $this->saturation;
        $m = $this->lightness - (0.5 * $c);

        if ($this->hue >= (1 / 3) && $this->hue < (2 / 3)) {
            return ($m * $max) + $min;
        } elseif ($this->hue >= (1 / 6) && $this->hue < (5 / 6)) {
            return ((($c * (1 - \abs(fmod(($this->hue * 6), 2) - 1))) + $m) * $max) + $min;
        } else {
            return (($c + $m) * $max) + $min;
        }
    }

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getGreen(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $c = (1 - \abs((2 * $this->lightness) - 1)) * $this->saturation;
        $m = $this->lightness - (0.5 * $c);

        if ($this->hue >= (2 / 3)) {
            return ($m * $max) + $min;
        } elseif ($this->hue >= (1 / 2) || $this->hue < (1 / 6)) {
            return ((($c * (1 - \abs(fmod($this->hue * 6, 2) - 1))) + $m) * $max) + $min;
        } else {
            return (($c + $m) * $max) + $min;
        }
    }

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getBlue(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $c = (1 - \abs((2 * $this->lightness) - 1)) * $this->saturation;
        $m = $this->lightness - (0.5 * $c);

        if ($this->hue < (1 / 3)) {
            return ($m * $max) + $min;
        } elseif ($this->hue < (1 / 2) || $this->hue >= (5 / 6)) {
            return ((($c * (1 - \abs(fmod($this->hue * 6, 2) - 1))) + $m) * $max) + $min;
        } else {
            return (($c + $m) * $max) + $min;
        }
    }

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getAlpha(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->alpha * $max) + $min;
    }

    /**
     * Gets the hue component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getHue(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->hue * $max) + $min;
    }

    /**
     * Gets the saturation component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getSaturation(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->saturation * $max) + $min;
    }

    /**
     * Gets the lightness/brightness component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getLightness(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->lightness * $max) + $min;
    }

    /**
     * Gets the color as a hex string.
     *
     * @return string
     */
    public function getHex(): string
    {
        return \sprintf(
            '%02X%02X%02X',
            (int)\round($this->getRed() * 0xFF),
            (int)\round($this->getGreen() * 0xFF),
            (int)\round($this->getBlue() * 0xFF)
        );
    }

    /**
     * Gets the color as a hex string, represented as an integer.
     *
     * @return int
     */
    public function getHexAsInt(): int
    {
        return
            ((int)\round($this->getRed() * 0xFF)) * 0x010000 +
            ((int)\round($this->getGreen() * 0xFF)) * 0x000100 +
            ((int)\round($this->getBlue() * 0xFF)) * 0x000001;
    }

    /**
     * Gets the RGB color as a three-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getRgb(float $min = 0.0, float $max = 1.0): array
    {
        return [$this->getRed($min, $max), $this->getGreen($min, $max), $this->getBlue($min, $max)];
    }

    /**
     * Gets the HSL color as a three-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getHsl(float $min = 0.0, float $max = 1.0): array
    {
        return [$this->getHue($min, $max), $this->getSaturation($min, $max), $this->getLightness($min, $max)];
    }

    /**
     * Gets the cyan component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getCyan(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $cmyk = $this->converter->rgbToCmyk($this->getRed(), $this->getGreen(), $this->getBlue());

            return ($cmyk[0] * $max) + $min;
        } else {
            $ob = 1 - $this->getBlack();

            return ($ob < 0.001 ? 0.0 : ((($ob - $this->getRed()) / $ob) * $max)) + $min;
        }
    }

    /**
     * Gets the magenta component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getMagenta(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $cmyk = $this->converter->rgbToCmyk($this->getRed(), $this->getGreen(), $this->getBlue());

            return ($cmyk[1] * $max) + $min;
        } else {
            $ob = 1 - $this->getBlack();

            return ($ob < 0.001 ? 0.0 : ((($ob - $this->getGreen()) / $ob) * $max)) + $min;
        }
    }

    /**
     * Gets the yellow component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getYellow(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $cmyk = $this->converter->rgbToCmyk($this->getRed(), $this->getGreen(), $this->getBlue());

            return ($cmyk[2] * $max) + $min;
        } else {
            $ob = 1 - $this->getBlack();

            return ($ob < 0.001 ? 0.0 : ((($ob - $this->getBlue()) / $ob) * $max)) + $min;
        }
    }

    /**
     * Gets the black component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getBlack(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $cmyk = $this->converter->rgbToCmyk($this->getRed(), $this->getGreen(), $this->getBlue());

            return ($cmyk[3] * $max) + $min;
        } else {
            return (\min(1 - $this->getRed(), 1 - $this->getBlue(), 1 - $this->getGreen()) * $max) + $min;
        }
    }

    /**
     * Gets the CMYK color as a four-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getCmyk(float $min = 0.0, float $max = 1.0): array
    {
        if ($this->converter && $this->converter->isEnabled()) {
            $cmyk = $this->converter->rgbToCmyk($this->getRed(), $this->getGreen(), $this->getBlue());

            return [
                ($cmyk[0] * $max) + $min,
                ($cmyk[1] * $max) + $min,
                ($cmyk[2] * $max) + $min,
                ($cmyk[3] * $max) + $min,
            ];
        } else {
            return [$this->getCyan($min, $max), $this->getMagenta($min, $max), $this->getYellow($min, $max), $this->getBlack($min, $max)];
        }
    }

    /**
     * Combines the supplied background Color with this color
     * taking into account the alpha value.
     *
     * @param Color $background The background color.
     * @return HslColor The mixed color.
     */
    public function getMixed(Color $background): Color
    {
        if ($this->getAlpha() + \PHP_FLOAT_EPSILON > 1.0) {
            return clone $this;
        }

        $fgRgb = new RgbColor($this->getRed(), $this->getGreen(), $this->getBlue(), $this->getAlpha(), $this->getConverter());

        $mixed = $fgRgb->getMixed($background);

        return new self($mixed->getHue(), $mixed->getSaturation(), $mixed->getLightness(), $mixed->getAlpha(), $mixed->getConverter());
    }

    /**
     * Sets the ColorConverter used to convert between CMYK and RGB/HSL.
     *
     * @param ColorConverter|null $converter
     */
    public function setConverter(?ColorConverter $converter): void
    {
        $this->converter = $converter;
    }

    /**
     * Gets the ColorConverter used to convert between CMYK and RGB/HSL.
     *
     * @return ColorConverter|null
     */
    public function getConverter(): ?ColorConverter
    {
        return $this->converter;
    }
}