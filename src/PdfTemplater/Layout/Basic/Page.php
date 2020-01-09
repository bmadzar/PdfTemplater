<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Layer;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\Page as PageInterface;

/**
 * Class Page
 *
 * A basic implementation of a Page.
 *
 * @package PdfTemplater\Layout\Basic
 */
class Page implements PageInterface
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var float
     */
    private $width;

    /**
     * @var float
     */
    private $height;

    /**
     * @var Layer[]
     */
    private $layers;

    /**
     * Page constructor.
     *
     * Sets the page number.
     *
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;

        $this->layers = [];
    }

    /**
     * Sets the width.
     *
     * @param float $width
     */
    public function setWidth(float $width): void
    {
        if ($width < \PHP_FLOAT_EPSILON) {
            throw new LayoutArgumentException('Page width must be > 0.00');
        }

        $this->width = $width;
    }

    /**
     * Gets the width.
     *
     * @return float
     */
    public function getWidth(): float
    {
        if ($this->width === null) {
            throw new LayoutArgumentException('Page not configured completely!');
        }

        return $this->width;
    }

    /**
     * Sets the height.
     *
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        if ($height < \PHP_FLOAT_EPSILON) {
            throw new LayoutArgumentException('Page height must be > 0.00');
        }

        $this->height = $height;
    }

    /**
     * Gets the height.
     *
     * @return float
     */
    public function getHeight(): float
    {
        if ($this->height === null) {
            throw new LayoutArgumentException('Page not configured completely!');
        }

        return $this->height;
    }

    /**
     * Sets the entire set of Layers.
     *
     * @param Layer[] $layers
     */
    public function setLayers(array $layers): void
    {
        foreach ($layers as $layer) {
            if ($layer instanceof Layer) {
                $this->addLayer($layer);
            } else {
                throw new LayoutArgumentException('Invalid Layer supplied!');
            }
        }
    }

    /**
     * Gets the set of Layers.
     *
     * @return Layer[]
     */
    public function getLayers(): array
    {
        return $this->layers;
    }

    /**
     * Adds the Layer to the Layer set.
     *
     * @param Layer $layer
     */
    public function addLayer(Layer $layer): void
    {
        $this->layers[$layer->getNumber()] = $layer;
    }

    /**
     * Gets the Layer by Layer number. Returns NULL if no such Layer exists.
     *
     * @param int $number
     * @return null|Layer
     */
    public function getLayer(int $number): ?Layer
    {
        return $this->layers[$number] ?? null;
    }

    /**
     * Removes the given Layer from the Layer set. Nothing should happen if the Layer is not
     * in the Layer set.
     *
     * @param Layer $layer
     */
    public function removeLayer(Layer $layer): void
    {
        $this->removeLayerByNumber($layer->getNumber());
    }

    /**
     * Removes the Layer with the given ID. Nothing should happen if the Layer is not
     * in the Layer set.
     *
     * @param int $number
     */
    public function removeLayerByNumber(int $number): void
    {
        unset($this->layers[$number]);
    }

    /**
     * Gets the page number.
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Sets the page number.
     *
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}