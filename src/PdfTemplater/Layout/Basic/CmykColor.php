<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\ColorConverter;
use PdfTemplater\Layout\ConvertibleColor;
use PdfTemplater\Layout\LayoutArgumentException;

class CmykColor implements ConvertibleColor
{
    /**
     * @var float
     */
    private float $cyan;

    /**
     * @var float
     */
    private float $magenta;

    /**
     * @var float
     */
    private float $yellow;

    /**
     * @var float
     */
    private float $black;

    /**
     * @var float
     */
    private float $alpha;

    /**
     * @var ColorConverter|null
     */
    private ?ColorConverter $converter;

    /**
     * CmykColor constructor.
     *
     * @param float               $cyan
     * @param float               $magenta
     * @param float               $yellow
     * @param float               $black
     * @param float               $alpha
     * @param ColorConverter|null $converter
     */
    public function __construct(float $cyan, float $magenta, float $yellow, float $black, float $alpha = 1.0, ?ColorConverter $converter = null)
    {
        $this->setCyan($cyan);
        $this->setMagenta($magenta);
        $this->setYellow($yellow);
        $this->setBlack($black);
        $this->setAlpha($alpha);
        $this->setConverter($converter);
    }

    /**
     * Sets the cyan value between 0 and 1.
     *
     * @param float $cyan
     */
    public function setCyan(float $cyan): void
    {
        if ($cyan < 0.00 || $cyan > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->cyan = $cyan;
    }

    /**
     * Sets the magenta value between 0 and 1.
     *
     * @param float $magenta
     */
    public function setMagenta(float $magenta): void
    {
        if ($magenta < 0.00 || $magenta > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->magenta = $magenta;
    }

    /**
     * Sets the yellow value between 0 and 1.
     *
     * @param float $yellow
     */
    public function setYellow(float $yellow): void
    {
        if ($yellow < 0.00 || $yellow > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->yellow = $yellow;
    }

    /**
     * Sets the black value between 0 and 1.
     *
     * @param float $black
     */
    public function setBlack(float $black): void
    {
        if ($black < 0.00 || $black > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->black = $black;
    }

    /**
     * Sets the alpha value between 0 and 1.
     *
     * @param float $alpha
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
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getRed(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $rgb = $this->converter->cmykToRgb($this->getCyan(), $this->getMagenta(), $this->getYellow(), $this->getBlack());

            return ($rgb[0] * $max) + $min;
        } else {
            return ((1 - $this->cyan) * (1 - $this->black) * $max) + $min;
        }
    }

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getGreen(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $rgb = $this->converter->cmykToRgb($this->getCyan(), $this->getMagenta(), $this->getYellow(), $this->getBlack());

            return ($rgb[1] * $max) + $min;
        } else {
            return ((1 - $this->magenta) * (1 - $this->black) * $max) + $min;
        }
    }

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getBlue(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        if ($this->converter && $this->converter->isEnabled()) {
            $rgb = $this->converter->cmykToRgb($this->getCyan(), $this->getMagenta(), $this->getYellow(), $this->getBlack());

            return ($rgb[2] * $max) + $min;
        } else {
            return ((1 - $this->yellow) * (1 - $this->black) * $max) + $min;
        }
    }

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
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
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getHue(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        [$r, $g, $b] = $this->getRgb();

        $cmax  = \max($r, $g, $b);
        $cmin  = \min($r, $g, $b);
        $delta = $cmax - $cmin;

        if ($delta < \PHP_FLOAT_EPSILON) {
            return 0.0;
        } elseif ($cmax === $r) {
            $h = ($g - $b) / $delta;
        } elseif ($cmax === $g) {
            $h = (($b - $r) / $delta) + 2.0;
        } elseif ($cmax === $b) {
            $h = (($r - $g) / $delta) + 4.0;
        } else {
            throw new LayoutArgumentException('CMYK/RGB to HSL conversion failed -- floating point issue?');
        }

        if ($h < 0.0) {
            $h += 6.0;
        } elseif ($h > 6.0) {
            $h = \fmod($h, 6.0);
        }

        return (($h / 6.0) * $max) + $min;
    }

    /**
     * Gets the saturation component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getSaturation(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        [$r, $g, $b] = $this->getRgb();

        $cmax  = \max($r, $g, $b);
        $cmin  = \min($r, $g, $b);
        $delta = $cmax - $cmin;

        if ($delta < \PHP_FLOAT_EPSILON) {
            return 0.0;
        } else {
            return (($delta / (1 - \abs($cmax + $cmin - 1))) * $max) + $min;
        }
    }

    /**
     * Gets the lightness/brightness component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getLightness(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        [$r, $g, $b] = $this->getRgb();

        $cmax  = \max($r, $g, $b);
        $cmin  = \min($r, $g, $b);

        return ((($cmax + $cmin) / 2) * $max) + $min;
    }

    /**
     * Gets the cyan component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getCyan(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->cyan * $max) + $min;
    }

    /**
     * Gets the magenta component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getMagenta(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->magenta * $max) + $min;
    }

    /**
     * Gets the yellow component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getYellow(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->yellow * $max) + $min;
    }

    /**
     * Gets the black component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max.
     */
    public function getBlack(float $min = 0.0, float $max = 1.0): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->black * $max) + $min;
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
        if ($this->converter && $this->converter->isEnabled()) {
            $rgb = $this->converter->cmykToRgb($this->getCyan(), $this->getMagenta(), $this->getYellow(), $this->getBlack());

            return [
                ($rgb[0] * $max) + $min,
                ($rgb[1] * $max) + $min,
                ($rgb[2] * $max) + $min,
            ];
        } else {
            return [$this->getRed($min, $max), $this->getGreen($min, $max), $this->getBlue($min, $max)];
        }
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
     * Gets the CMYK color as a four-element array.
     *
     * @param float $min
     * @param float $max
     * @return float[]
     */
    public function getCmyk(float $min = 0.0, float $max = 1.0): array
    {
        return [$this->getCyan($min, $max), $this->getMagenta($min, $max), $this->getYellow($min, $max), $this->getBlack($min, $max)];
    }

    /**
     * Combines the supplied background Color with this color
     * taking into account the alpha value.
     *
     * @param Color $background The background color.
     * @return CmykColor The mixed color.
     */
    public function getMixed(Color $background): Color
    {
        if ($this->getAlpha() + \PHP_FLOAT_EPSILON > 1.0) {
            return clone $this;
        }

        $bc = $background->getCyan();
        $bm = $background->getMagenta();
        $by = $background->getYellow();
        $bk = $background->getBlack();
        $ba = $background->getAlpha();

        $fc = $this->getCyan();
        $fm = $this->getMagenta();
        $fy = $this->getYellow();
        $fk = $this->getBlack();
        $fa = $this->getAlpha();

        $na = $fa + ($ba * (1 - $fa));

        if ($na > \PHP_FLOAT_EPSILON) {
            $nc = (((1 - $fa) * $ba * $bc) + ($fa * $fc)) / $na;
            $nm = (((1 - $fa) * $ba * $bm) + ($fa * $fm)) / $na;
            $ny = (((1 - $fa) * $ba * $by) + ($fa * $fy)) / $na;
            $nk = (((1 - $fa) * $ba * $bk) + ($fa * $fk)) / $na;

            return new self($nc, $nm, $ny, $nk, $na, $this->getConverter());
        } else {
            return new self(0.0, 0.0, 0.0, 0.0, 0.0, $this->getConverter());
        }
    }

    /**
     * Sets the ColorConverter used to convert between CMYK and RGB.
     *
     * @param ColorConverter|null $converter
     */
    public function setConverter(?ColorConverter $converter): void
    {
        $this->converter = $converter;
    }

    /**
     * Gets the ColorConverter used to convert between CMYK and RGB.
     *
     * @return ColorConverter|null
     */
    public function getConverter(): ?ColorConverter
    {
        return $this->converter;
    }
}