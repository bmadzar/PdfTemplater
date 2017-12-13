<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;


interface ImageElement extends RectangleElement
{
    /**
     * Sets the image data.
     *
     * @param string $image
     */
    public function setImageData(string $image): void;

    /**
     * Gets the image data.
     *
     * @return string
     */
    public function getImageData(): string;
}