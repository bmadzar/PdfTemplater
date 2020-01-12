<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface Element
 *
 * An Element is a basic drawable item that covers a rectangular area on a Page.
 *
 * @package PdfTemplater\Layout
 */
interface Element
{
    /**
     * Sets the left offset.
     *
     * @param float $left
     */
    public function setLeft(float $left): void;

    /**
     * Gets the left offset.
     *
     * @return float
     */
    public function getLeft(): float;

    /**
     * Sets the top offset.
     *
     * @param float $top
     */
    public function setTop(float $top): void;

    /**
     * Gets the top offset.
     *
     * @return float
     */
    public function getTop(): float;

    /**
     * Sets the width.
     *
     * @param float $width
     */
    public function setWidth(float $width): void;

    /**
     * Gets the width.
     *
     * @return float
     */
    public function getWidth(): float;

    /**
     * Sets the height.
     *
     * @param float $height
     */
    public function setHeight(float $height): void;

    /**
     * Gets the height.
     *
     * @return float
     */
    public function getHeight(): float;

    /**
     * Sets the unique identifier.
     *
     * @param string $id
     */
    public function setId(string $id): void;

    /**
     * Gets the unique identifier.
     *
     * @return string
     */
    public function getId(): string;
}