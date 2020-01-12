<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Color;
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
    private string $data;

    /**
     * @var null|string
     */
    private ?string $temp;

    /**
     * @var null|string
     */
    private ?string $altText;

    /**
     * DataImageElement constructor.
     *
     * @param string      $id
     * @param float       $left
     * @param float       $top
     * @param float       $width
     * @param float       $height
     * @param Color|null  $stroke
     * @param float|null  $strokeWidth
     * @param Color|null  $fill
     * @param string      $data
     * @param string|null $altText
     */
    public function __construct(string $id, float $left, float $top, float $width, float $height, ?Color $stroke, ?float $strokeWidth, ?Color $fill, string $data, ?string $altText)
    {
        parent::__construct($id, $left, $top, $width, $height, $stroke, $strokeWidth, $fill);

        $this->setData($data);
        $this->setAltText($altText);
    }

    /**
     * Sets the base64 data.
     *
     * @param string $data
     */
    public function setData(string $data): void
    {
        $data = \strtr($data, ['-' => '+', '_' => '/']);

        if (!\preg_match('/^[a-zA-Z0-9+\/]+={0,3}$/', $data)) {
            throw new LayoutArgumentException('Invalid base64 data supplied.');
        }

        $this->data = $data . \str_repeat('=', (4 - (\strlen($data) % 4) % 4));
    }

    /**
     * Sets the data in raw binary form.
     *
     * @param string $data
     */
    public function setBinaryData(string $data): void
    {
        $this->data = \base64_encode($data);
    }

    /**
     * Gets the image data as base64.
     *
     * @return string
     */
    public function getImageData(): string
    {
        return $this->data;
    }

    /**
     * Gets the image as a filename on disk.
     *
     * @return string
     */
    public function getImageFile(): string
    {
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