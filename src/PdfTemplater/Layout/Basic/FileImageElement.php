<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


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
    private $file;

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
        if ($this->file === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return \hash_file('base64', $this->file, false);
    }

    /**
     * Gets the image as a filename on disk.
     *
     * @return string
     */
    public function getImageFile(): string
    {
        if ($this->file === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->file;
    }

    /**
     * Elements can be partially constructed. This method should return true if and only if
     * all mandatory values have been set.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return parent::isValid() && $this->file;
    }
}