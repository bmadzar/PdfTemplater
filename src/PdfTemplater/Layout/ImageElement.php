<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface ImageElement
 *
 * An image. The image source can be determined by implementing classes as long as
 * the data can be obtained as either a file on disk or as base64.
 *
 * @package PdfTemplater\Layout
 */
interface ImageElement extends RectangleElement
{
    /**
     * Gets the image data as base64.
     *
     * @return string
     */
    public function getImageData(): string;

    /**
     * Gets the image as a filename on disk.
     *
     * @return string
     */
    public function getImageFile(): string;

    /**
     * Sets the alt text.
     *
     * @param null|string $text
     */
    public function setAltText(?string $text): void;

    /**
     * Gets the alt text.
     *
     * @return null|string
     */
    public function getAltText(): ?string;
}