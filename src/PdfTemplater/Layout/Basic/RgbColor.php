<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class RgbColor
 *
 * Color class for RGBA values.
 *
 * @package PdfTemplater\Layout\Basic
 */
class RgbColor implements Color
{
    /**
     * @var float
     */
    private $red;

    /**
     * @var float
     */
    private $green;

    /**
     * @var float
     */
    private $blue;

    /**
     * @var float
     */
    private $alpha;

    /**
     * RgbColor constructor.
     *
     * @param float $red
     * @param float $green
     * @param float $blue
     * @param float $alpha
     */
    public function __construct(float $red, float $green, float $blue, float $alpha = 1.0)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
        $this->setAlpha($alpha);
    }

    /**
     * Creates an RgbColor from a hex string.
     *
     * @param string $hex
     * @return RgbColor
     */
    public static function createFromHex(string $hex): self
    {
        $hex = \ltrim('#', \trim($hex));

        if ((\strlen($hex) !== 3 && \strlen($hex) !== 6) || !\preg_match('/^[0-9af]+$/i', $hex)) {
            throw new LayoutArgumentException('Invalid hex value supplied!');
        }

        return new self(
            (float)\base_convert(\substr($hex, 0, 2), 16, 10),
            (float)\base_convert(\substr($hex, 2, 2), 16, 10),
            (float)\base_convert(\substr($hex, 4, 2), 16, 10),
            1.0
        );
    }

    /**
     * Sets the red value -- between 0 and 1.
     *
     * @param float $red
     */
    public function setRed(float $red): void
    {
        if ($red < 0.00 || $red > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->red = $red;
    }

    /**
     * Sets the green value -- between 0 and 1.
     *
     * @param float $green
     */
    public function setGreen(float $green): void
    {
        if ($green < 0.00 || $green > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->green = $green;
    }

    /**
     * Sets the blue value -- between 0 and 1.
     *
     * @param float $blue
     */
    public function setBlue(float $blue): void
    {
        if ($blue < 0.00 || $blue > 1.00) {
            throw new LayoutArgumentException('Invalid colour value!');
        }

        $this->blue = $blue;
    }

    /**
     * Sets the alpha value -- between 0 and 1.
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
        return ($this->red * $max) + $min;
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
        return ($this->green * $max) + $min;
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
        return ($this->blue * $max) + $min;
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
        $cmax = \max($this->red, $this->green, $this->blue);
        $cmin = \min($this->red, $this->green, $this->blue);
        $delta = $cmax - $cmin;

        if ($delta < 0.001) {
            return 0.0;
        } elseif ($cmax === $this->red) {
            return ((((($this->green - $this->blue) / $delta) % 6) / 6) * $max) + $min;
        } elseif ($cmax === $this->green) {
            return ((((($this->blue - $this->red) / $delta) + 2) / 6) * $max) + $min;
        } elseif ($cmax === $this->blue) {
            return ((((($this->red - $this->green) / $delta) + 4) / 6) * $max) + $min;
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
        $cmax = \max($this->red, $this->green, $this->blue);
        $cmin = \min($this->red, $this->green, $this->blue);
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
        $cmax = \max($this->red, $this->green, $this->blue);
        $cmin = \min($this->red, $this->green, $this->blue);

        return ((($cmax + $cmin) / 2) * $max) + $min;
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
            (int)\round($this->red * 0xFF),
            (int)\round($this->green * 0xFF),
            (int)\round($this->blue * 0xFF)
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
            ((int)\round($this->red * 0xFF)) * 0x010000 +
            ((int)\round($this->green * 0xFF)) * 0x000100 +
            ((int)\round($this->blue * 0xFF)) * 0x000001;
    }

    /**
     * Gets the RGB color as a three-element array.
     *
     * @return array
     */
    public function getRgb(): array
    {
        return [$this->red, $this->green, $this->blue];
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
     * Gets the cyan component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     */
    public function getCyan(float $min = 0, float $max = 1): float
    {
        $ob = 1 - $this->getBlack();

        return ((($ob - $this->red) / $ob) * $max) + $min;
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
        $ob = 1 - $this->getBlack();

        return ((($ob - $this->green) / $ob) * $max) + $min;
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
        $ob = 1 - $this->getBlack();

        return ((($ob - $this->blue) / $ob) * $max) + $min;
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
        return (\min(1 - $this->red, 1 - $this->blue, 1 - $this->green) * $max) + $min;
    }

    /**
     * Gets the CMYK color as a four-element array.
     *
     * @return array
     */
    public function getCmyk(): array
    {
        return [$this->getCyan(), $this->getMagenta(), $this->getYellow(), $this->getBlack()];
    }

    /**
     * Combines the supplied background Color with this color
     * taking into account the alpha value.
     *
     * @param Color $background The background color.
     * @return RgbColor The mixed color.
     */
    public function getMixed(Color $background): Color
    {
        $br = $background->getRed();
        $bg = $background->getGreen();
        $bb = $background->getBlue();
        $ba = $background->getAlpha();

        $fr = $this->getRed();
        $fg = $this->getGreen();
        $fb = $this->getBlue();
        $fa = $this->getAlpha();

        $nr = ((1 - $fa) * $br) + ($fa * $fr);
        $ng = ((1 - $fa) * $bg) + ($fa * $fg);
        $nb = ((1 - $fa) * $bb) + ($fa * $fb);
        $na = $ba + ((1 - $ba) * $fa);

        return new self($nr, $ng, $nb, $na);
    }
}