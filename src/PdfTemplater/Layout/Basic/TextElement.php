<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\TextElement as TextElementInterface;

class TextElement extends RectangleElement implements TextElementInterface
{

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $font;

    /**
     * @var float
     */
    private $fontSize;

    /**
     * @var float
     */
    private $lineSize;

    /**
     * @var int
     */
    private $wrapMode;

    /**
     * @var int
     */
    private $alignMode;

    /**
     * @var int
     */
    private $verticalAlignMode;

    /**
     * @var Color
     */
    private $color;

    /**
     * Sets the text.
     *
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Gets the text.
     *
     * @return string
     */
    public function getText(): string
    {
        if ($this->text === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->text;
    }

    /**
     * Sets the font face.
     *
     * @param string $font
     */
    public function setFont(string $font): void
    {
        $this->font = $font;
    }

    /**
     * Gets the font face.
     *
     * @return string
     */
    public function getFont(): string
    {
        if ($this->font === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->font;
    }

    /**
     * Sets the font size.
     *
     * @param float $size
     */
    public function setFontSize(float $size): void
    {
        if ($size < \PHP_FLOAT_EPSILON) {
            throw new LayoutArgumentException('Font size must be greater than 0.');
        }

        $this->fontSize = $size;

        if ($this->lineSize === null) {
            $this->setLineSize($size);
        }
    }

    /**
     * Gets the font size.
     *
     * @return float
     */
    public function getFontSize(): float
    {
        if ($this->fontSize === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->fontSize;
    }

    /**
     * Sets the line size.
     *
     * @param float $size
     */
    public function setLineSize(float $size): void
    {
        if ($size < \PHP_FLOAT_EPSILON) {
            throw new LayoutArgumentException('Line size must be greater than 0.');
        }

        $this->lineSize = $size;
    }

    /**
     * Gets the line size.
     *
     * @return float
     */
    public function getLineSize(): float
    {
        if ($this->lineSize === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->lineSize;
    }

    /**
     * Sets the wrapping mode, one of the WRAP_* constants.
     *
     * @param int $wrapMode
     */
    public function setWrapMode(int $wrapMode): void
    {
        if (!\in_array($wrapMode, self::WRAPS, true)) {
            throw new LayoutArgumentException('Invalid wrap mode supplied.');
        }

        $this->wrapMode = $wrapMode;
    }

    /**
     * Gets the wrapping mode, one of the WRAP_* constants.
     *
     * @return int
     */
    public function getWrapMode(): int
    {
        if ($this->wrapMode === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->wrapMode;
    }

    /**
     * Sets the horizontal alignment mode, one of the ALIGN_* constants.
     *
     * @param int $alignMode
     */
    public function setAlignMode(int $alignMode): void
    {
        if (!\in_array($alignMode, self::ALIGNS, true)) {
            throw new LayoutArgumentException('Invalid wrap mode supplied.');
        }

        $this->alignMode = $alignMode;
    }

    /**
     * Gets the horizontal alignment mode, one of the ALIGN_* constants.
     *
     * @return int
     */
    public function getAlignMode(): int
    {
        if ($this->alignMode === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->alignMode;
    }

    /**
     * Sets the vertical alignment mode, one of the VERTICAL_ALIGN_* constants.
     *
     * @param int $alignMode
     */
    public function setVerticalAlignMode(int $alignMode): void
    {
        if (!\in_array($alignMode, self::VERTICAL_ALIGNS, true)) {
            throw new LayoutArgumentException('Invalid wrap mode supplied.');
        }

        $this->verticalAlignMode = $alignMode;
    }

    /**
     * Gets the vertical alignment mode, one of the VERTICAL_ALIGN_* constants.
     *
     * @return int
     */
    public function getVerticalAlignMode(): int
    {
        if ($this->verticalAlignMode === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->verticalAlignMode;
    }

    /**
     * Sets the text color.
     *
     * @param Color $color
     */
    public function setColor(Color $color): void
    {
        $this->color = $color;
    }

    /**
     * Gets the text color.
     *
     * @return Color
     */
    public function getColor(): Color
    {
        if ($this->color === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->color;
    }

    /**
     * Elements can be partially constructed. This method should return true if and only if
     * all mandatory values have been set.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return parent::isValid() &&
            ($this->text || $this->text === '') &&
            $this->fontSize &&
            $this->font &&
            $this->lineSize &&
            $this->color &&
            $this->wrapMode !== null &&
            $this->alignMode !== null &&
            $this->verticalAlignMode !== null;
    }
}