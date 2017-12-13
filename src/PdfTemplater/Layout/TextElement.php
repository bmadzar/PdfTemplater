<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;


interface TextElement extends RectangleElement
{
    public const WRAP_HARD = 2;
    public const WRAP_SOFT = 1;
    public const WRAP_NONE = 0;

    public const ALIGN_JUSTIFY = 3;
    public const ALIGN_RIGHT = 2;
    public const ALIGN_CENTER = 1;
    public const ALIGN_LEFT = 0;

    public const VERTICAL_ALIGN_BOTTOM = 2;
    public const VERTICAL_ALIGN_MIDDLE = 1;
    public const VERTICAL_ALIGN_TOP = 0;

    /**
     * Sets the text.
     *
     * @param string $text
     */
    public function setText(string $text): void;

    /**
     * Gets the text.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Sets the font face.
     *
     * @param string $font
     */
    public function setFont(string $font): void;

    /**
     * Gets the font face.
     *
     * @return string
     */
    public function getFont(): string;

    /**
     * Sets the font size.
     *
     * @param float $size
     */
    public function setFontSize(float $size): void;

    /**
     * Gets the font size.
     *
     * @return float
     */
    public function getFontSize(): float;

    /**
     * Sets the line size.
     *
     * @param float $size
     */
    public function setLineSize(float $size): void;

    /**
     * Gets the line size.
     *
     * @return float
     */
    public function getLineSize(): float;

    /**
     * Sets the wrapping mode, one of the WRAP_* constants.
     *
     * @param int $wrapMode
     */
    public function setWrapMode(int $wrapMode): void;

    /**
     * Gets the wrapping mode, one of the WRAP_* constants.
     *
     * @return int
     */
    public function getWrapMode(): int;

    /**
     * Sets the horizontal alignment mode, one of the ALIGN_* constants.
     *
     * @param int $alignMode
     */
    public function setAlignMode(int $alignMode): void;

    /**
     * Gets the horizontal alignment mode, one of the ALIGN_* constants.
     *
     * @return int
     */
    public function getAlignMode(): int;

    /**
     * Sets the vertical alignment mode, one of the VERTICAL_ALIGN_* constants.
     *
     * @param int $alignMode
     */
    public function setVerticalAlignMode(int $alignMode): void;

    /**
     * Gets the vertical alignment mode, one of the VERTICAL_ALIGN_* constants.
     *
     * @return int
     */
    public function getVerticalAlignMode(): int;

    /**
     * Sets the text color.
     *
     * @param Color $color
     */
    public function setColor(Color $color): void;

    /**
     * Gets the text color.
     *
     * @return Color
     */
    public function getColor(): Color;

}