<?php
declare(strict_types=1);


namespace PdfTemplater\Layout;

/**
 * Interface Font
 *
 * A font, either loaded from a file or one of the standard PDF fonts.
 *
 * @package PdfTemplater\Layout
 */
interface Font
{
    public const STYLE_NORMAL = 0;
    public const STYLE_BOLD = 1;
    public const STYLE_ITALIC = 2;
    public const STYLE_BOLD_ITALIC = self::STYLE_BOLD | self::STYLE_ITALIC;

    public const STYLES = [
        self::STYLE_NORMAL,
        self::STYLE_BOLD,
        self::STYLE_ITALIC,
        self::STYLE_BOLD_ITALIC,
    ];

    /**
     * Font constructor.
     *
     * @param string $name
     * @param int $style
     * @param string|null $file
     */
    public function __construct(string $name, int $style, ?string $file);

    /**
     * Sets the font name.
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Gets the font name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the style as one of the STYLE_* constants.
     *
     * @param int $style
     */
    public function setStyle(int $style): void;

    /**
     * Gets the style as one of the the STYLE_* constants.
     *
     * @return int
     */
    public function getStyle(): int;

    /**
     * Sets the file name, or null if this is a standard PDF font.
     *
     * @param string|null $file
     */
    public function setFile(?string $file): void;

    /**
     * Returns the file name, or null if this is a standard PDF font.
     *
     * @return string|null
     */
    public function getFile(): ?string;
}