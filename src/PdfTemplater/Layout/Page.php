<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface Page
 *
 * The Page represents a contiguous region on which Elements are placed. It contains a set of Layers.
 * Neither an Element nor a Layer can span Pages.
 *
 * @package PdfTemplater\Layout
 */
interface Page
{
    /**
     * Page constructor.
     *
     * Sets the page number.
     *
     * @param int $number
     */
    public function __construct(int $number);

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
     * Sets the entire set of Layers.
     *
     * @param Layer[] $layers
     */
    public function setLayers(array $layers): void;

    /**
     * Gets the set of Layers.
     *
     * @return Layer[]
     */
    public function getLayers(): array;

    /**
     * Adds the Layer to the Layer set.
     *
     * @param Layer $layer
     */
    public function addLayer(Layer $layer): void;

    /**
     * Gets the Layer by Layer number. Returns NULL if no such Layer exists.
     *
     * @param int $number
     * @return null|Layer
     */
    public function getLayer(int $number): ?Layer;

    /**
     * Removes the given Layer from the Layer set. Nothing should happen if the Layer is not
     * in the Layer set.
     *
     * @param Layer $layer
     */
    public function removeLayer(Layer $layer): void;

    /**
     * Removes the Layer with the given ID. Nothing should happen if the Layer is not
     * in the Layer set.
     *
     * @param int $number
     */
    public function removeLayerById(int $number): void;

    /**
     * Gets the page number.
     *
     * @return int
     */
    public function getNumber(): int;

    /**
     * Sets the page number.
     *
     * @param int $number
     */
    public function setNumber(int $number): void;
}