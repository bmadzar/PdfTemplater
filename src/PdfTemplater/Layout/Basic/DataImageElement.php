<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\ImageElement;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class DataImageElement
 *
 * An implementation of an Image Element that accepts data as a base64 string.
 *
 * @package PdfTemplater\Layout\Basic
 */
class DataImageElement extends RectangleElement implements ImageElement
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var null|string
     */
    private $temp;

    /**
     * Sets the base64 data.
     *
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * Gets the image data as base64.
     *
     * @return string
     */
    public function getImageData(): string
    {
        if ($this->data === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->data;
    }

    /**
     * Gets the image as a filename on disk.
     *
     * @return string
     */
    public function getImageFile(): string
    {
        if ($this->data === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        if (!$this->temp) {
            $this->temp = \tempnam(\sys_get_temp_dir(), 'pdftemplater');
        }

        \file_put_contents($this->temp, \base64_decode($this->data));

        return $this->temp;
    }

    /**
     * Cleans up temporary files.
     */
    public function __destruct()
    {
        if ($this->temp) {
            \unlink($this->temp);
        }
    }

    /**
     * Elements can be partially constructed. This method should return true if and only if
     * all mandatory values have been set.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return parent::isValid() && $this->data;
    }
}