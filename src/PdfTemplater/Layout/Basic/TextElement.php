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
    private string $text;

    /**
     * @var string
     */
    private string $font;

    /**
     * @var float
     */
    private float $fontSize;

    /**
     * @var float
     */
    private float $lineSize;

    /**
     * @var int
     */
    private int $wrapMode;

    /**
     * @var int
     */
    private int $alignMode;

    /**
     * @var int
     */
    private int $verticalAlignMode;

    /**
     * @var Color
     */
    private Color $color;

    public function __construct(
        string $id,
        float $left,
        float $top,
        float $width,
        float $height,
        ?Color $stroke,
        ?float $strokeWidth,
        ?Color $fill,
        string $text,
        string $font,
        float $fontSize,
        float $lineSize,
        int $wrapMode,
        int $alignMode,
        int $verticalAlignMode
    )
    {
        parent::__construct($id, $left, $top, $width, $height, $stroke, $strokeWidth, $fill);

        $this->setText($text);
        $this->setFont($font);
        $this->setFontSize($fontSize);
        $this->setLineSize($lineSize);
        $this->setWrapMode($wrapMode);
        $this->setAlignMode($alignMode);
        $this->setVerticalAlignMode($verticalAlignMode);
    }

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
    }

    /**
     * Gets the font size.
     *
     * @return float
     */
    public function getFontSize(): float
    {
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
        return clone $this->color;
    }
}