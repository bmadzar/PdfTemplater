<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;

class CmykColor implements Color
{
    /**
     * @var float
     */
    private $cyan;

    /**
     * @var float
     */
    private $magenta;

    /**
     * @var float
     */
    private $yellow;

    /**
     * @var float
     */
    private $black;

    /**
     * @var float
     */
    private $alpha;

    /**
     * CmykColor constructor.
     *
     * @param float $cyan
     * @param float $magenta
     * @param float $yellow
     * @param float $black
     * @param float $alpha
     */
    public function __construct(float $cyan, float $magenta, float $yellow, float $black, float $alpha = 1.0)
    {
        $this->setCyan($cyan);
        $this->setMagenta($magenta);
        $this->setYellow($yellow);
        $this->setBlack($black);
        $this->setAlpha($alpha);
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
     */
    public function getRed(float $min = 0, float $max = 1): float
    {
        return (($this->cyan - 1) * (1 - $this->black) * $max) + $min;
    }

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getGreen(float $min = 0, float $max = 1): float
    {
        return (($this->magenta - 1) * (1 - $this->black) * $max) + $min;
    }

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getBlue(float $min = 0, float $max = 1): float
    {
        return (($this->yellow - 1) * (1 - $this->black) * $max) + $min;
    }

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getAlpha(float $min = 0, float $max = 1): float
    {
        return ($this->alpha * $max) + $min;
    }

    /**
     * Gets the hue component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getHue(float $min = 0, float $max = 1): float
    {
        $cmax = \max($this->getRed(), $this->getGreen(), $this->getBlue());
        $cmin = \min($this->getRed(), $this->getGreen(), $this->getBlue());
        $delta = $cmax - $cmin;

        if ($delta < 0.001) {
            return 0.0;
        } elseif ($cmax === $this->getRed()) {
            return ((((($this->getGreen() - $this->getBlue()) / $delta) % 6) / 6) * $max) + $min;
        } elseif ($cmax === $this->getGreen()) {
            return ((((($this->getBlue() - $this->getRed()) / $delta) + 2) / 6) * $max) + $min;
        } elseif ($cmax === $this->getBlue()) {
            return ((((($this->getRed() - $this->getGreen()) / $delta) + 4) / 6) * $max) + $min;
        } else {
            throw new LayoutArgumentException('RGB to HSL conversion failed - floating point issue?');
        }
    }

    /**
     * Gets the saturation component of HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getSaturation(float $min = 0, float $max = 1): float
    {
        $cmax = \max($this->getRed(), $this->getGreen(), $this->getBlue());
        $cmin = \min($this->getRed(), $this->getGreen(), $this->getBlue());
        $delta = $cmax - $cmin;

        if ($delta < 0.001) {
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
     */
    public function getLightness(float $min = 0, float $max = 1): float
    {
        $cmax = \max($this->getRed(), $this->getGreen(), $this->getBlue());
        $cmin = \min($this->getRed(), $this->getGreen(), $this->getBlue());

        return ((($cmax + $cmin) / 2) * $max) + $min;
    }

    /**
     * Gets the cyan component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getCyan(float $min = 0, float $max = 1): float
    {
        return ($this->cyan * $max) + $min;
    }

    /**
     * Gets the magenta component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getMagenta(float $min = 0, float $max = 1): float
    {
        return ($this->magenta * $max) + $min;
    }

    /**
     * Gets the yellow component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getYellow(float $min = 0, float $max = 1): float
    {
        return ($this->yellow * $max) + $min;
    }

    /**
     * Gets the black component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getBlack(float $min = 0, float $max = 1): float
    {
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
     * @return array
     */
    public function getRgb(): array
    {
        return [$this->getRed(), $this->getGreen(), $this->getBlue()];
    }

    /**
     * Gets the HSL color as a three-element array.
     *
     * @return array
     */
    public function getHsl(): array
    {
        return [$this->getHue(), $this->getSaturation(), $this->getLightness()];
    }

    /**
     * Gets the CMYK color as a four-element array.
     *
     * @return array
     */
    public function getCmyk(): array
    {
        return [$this->cyan, $this->magenta, $this->yellow, $this->black];
    }
}