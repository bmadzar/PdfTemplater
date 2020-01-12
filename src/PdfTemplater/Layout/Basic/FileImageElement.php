<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
use PdfTemplater\Layout\ImageElement;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class FileImageElement
 *
 * An implementation of an Image Element that uses a file on disk for data.
 *
 * @package PdfTemplater\Layout\Basic
 */
class FileImageElement extends RectangleElement implements ImageElement
{
    /**
     * @var string
     */
    private string $file;

    /**
     * @var null|string
     */
    private ?string $altText;

    public function __construct(string $id, float $left, float $top, float $width, float $height, ?Color $stroke, ?float $strokeWidth, ?Color $fill, string $file, ?string $altText)
    {
        parent::__construct($id, $left, $top, $width, $height, $stroke, $strokeWidth, $fill);

        $this->setFile($file);
        $this->setAltText($altText);
    }

    /**
     * Sets the filename.
     *
     * @param string $file
     */
    public function setFile(string $file): void
    {
        if (!\is_readable($file) || !\is_file($file)) {
            throw new LayoutArgumentException('Invalid file supplied!');
        }

        $this->file = $file;
    }

    /**
     * Gets the image data as base64.
     *
     * @return string
     */
    public function getImageData(): string
    {
        return \hash_file('base64', $this->file, false);
    }

    /**
     * Gets the image as a filename on disk.
     *
     * @return string
     */
    public function getImageFile(): string
    {
        return $this->file;
    }

    /**
     * Sets the alt text.
     *
     * @param null|string $text
     */
    public function setAltText(?string $text): void
    {
        $this->altText = $text;
    }

    /**
     * Gets the alt text.
     *
     * @return null|string
     */
    public function getAltText(): ?string
    {
        return $this->altText;
    }
}