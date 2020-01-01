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
    private const GAMMA = 2.2;

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
     * @throws LayoutArgumentException If $hex is not a valid hex string
     */
    public static function createFromHex(string $hex): self
    {
        $hex = \ltrim(\trim($hex), '#');

        if ((\strlen($hex) !== 3 && \strlen($hex) !== 6) || !\preg_match('/^[0-9A-Fa-f]+$/', $hex)) {
            throw new LayoutArgumentException('Invalid hex value supplied!');
        }

        if (\strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return new self(
            (float)\base_convert(\substr($hex, 0, 2), 16, 10) / 0xFF,
            (float)\base_convert(\substr($hex, 2, 2), 16, 10) / 0xFF,
            (float)\base_convert(\substr($hex, 4, 2), 16, 10) / 0xFF,
            1.0
        );
    }

    /**
     * Sets the red value -- between 0 and 1.
     *
     * @param float $red
     * @throws LayoutArgumentException If $red is out of range
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
     * @throws LayoutArgumentException If $green is out of range
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
     * @throws LayoutArgumentException If $blue is out of range
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
    public function getRed(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->red * $max) + $min;
    }

    /**
     * Gets the green component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getGreen(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->green * $max) + $min;
    }

    /**
     * Gets the blue component of RGBA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getBlue(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        return ($this->blue * $max) + $min;
    }

    /**
     * Gets the alpha component of RGBA/HSLA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getAlpha(float $min = 0, float $max = 1): float
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
    public function getHue(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $cmax = \max($this->red, $this->green, $this->blue);
        $cmin = \min($this->red, $this->green, $this->blue);
        $delta = $cmax - $cmin;

        if ($delta < 0.001) {
            return 0.0;
        } elseif ($cmax === $this->red) {
            $h = ($this->green - $this->blue) / $delta;
        } elseif ($cmax === $this->green) {
            $h = (($this->blue - $this->red) / $delta) + 2.0;
        } elseif ($cmax === $this->blue) {
            $h = (($this->red - $this->green) / $delta) + 4.0;
        } else {
            throw new LayoutArgumentException('RGB to HSL conversion failed -- floating point issue?');
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
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getSaturation(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

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
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getLightness(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

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
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getCyan(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $ob = 1 - $this->getBlack();

        return ((($ob - $this->red) / $ob) * $max) + $min;
    }

    /**
     * Gets the magenta component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getMagenta(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $ob = 1 - $this->getBlack();

        return ((($ob - $this->green) / $ob) * $max) + $min;
    }

    /**
     * Gets the yellow component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getYellow(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

        $ob = 1 - $this->getBlack();

        return ((($ob - $this->blue) / $ob) * $max) + $min;
    }

    /**
     * Gets the black component of CMYKA.
     *
     * @param float $min
     * @param float $max
     * @return float
     * @throws LayoutArgumentException If $min >= $max
     */
    public function getBlack(float $min = 0, float $max = 1): float
    {
        if ($min >= $max) {
            throw new LayoutArgumentException('Min must be less than max.');
        }

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

    private function applyGamma(float $val): float
    {
        if ($val < 0.0 || $val > 1.0) {
            throw new LayoutArgumentException('RGB value must be between 0.0 and 1.0.');
        }

        if ($val <= 0.0031308) {
            return 12.92 * $val;
        } else {
            return (1.055 * ($val ** (5 / 12))) - 0.055;
        }
    }

    private function removeGamma(float $val): float
    {
        if ($val < 0.0 || $val > 1.0) {
            throw new LayoutArgumentException('RGB value must be between 0.0 and 1.0.');
        }

        if ($val <= 0.04045) {
            return $val / 12.92;
        } else {
            return (($val + 0.055) / 1.055) ** 2.4;
        }
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

        $na = $fa + ($ba * (1 - $fa));

        if ($na > \PHP_FLOAT_EPSILON) {
            $nr = (((1 - $fa) * $ba * $this->removeGamma($br)) + ($fa * $this->removeGamma($fr))) / $na;
            $ng = (((1 - $fa) * $ba * $this->removeGamma($bg)) + ($fa * $this->removeGamma($fg))) / $na;
            $nb = (((1 - $fa) * $ba * $this->removeGamma($bb)) + ($fa * $this->removeGamma($fb))) / $na;

            $nr = $this->applyGamma($nr);
            $ng = $this->applyGamma($ng);
            $nb = $this->applyGamma($nb);

            return new self($nr, $ng, $nb, $na);
        } else {
            return new self(0.0, 0.0, 0.0, 0.0);
        }
    }
}