<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Element as ElementInterface;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class Element
 *
 * A simple implementation of the common Element interface.
 *
 * @package PdfTemplater\Layout\Basic
 */
class Element implements ElementInterface
{
    /**
     * @var string The element identifier.
     */
    private string $id;

    /**
     * @var float The offset from the left edge of the page.
     */
    private float $left;

    /**
     * @var float The offset from the top edge of the page.
     */
    private float $top;

    /**
     * @var float The width of the element box.
     */
    private float $width;

    /**
     * @var float The height of the element box.
     */
    private float $height;

    /**
     * Element constructor.
     *
     * Elements require a unique identifier.
     *
     * @param string $id
     * @param float  $left
     * @param float  $top
     * @param float  $width
     * @param float  $height
     */
    public function __construct(string $id, float $left, float $top, float $width, float $height)
    {
        $this->setId($id);
        $this->setLeft($left);
        $this->setTop($top);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * Sets the left offset.
     *
     * @param float $left
     */
    public function setLeft(float $left): void
    {
        $this->left = $left;
    }

    /**
     * Gets the left offset.
     *
     * @return float
     */
    public function getLeft(): float
    {
        return $this->left;
    }

    /**
     * Sets the top offset.
     *
     * @param float $top
     */
    public function setTop(float $top): void
    {
        $this->top = $top;
    }

    /**
     * Gets the top offset.
     *
     * @return float
     */
    public function getTop(): float
    {
        return $this->top;
    }

    /**
     * Sets the width.
     *
     * @param float $width
     */
    public function setWidth(float $width): void
    {
        if ($width < 0.00) {
            throw new LayoutArgumentException('Element width must be 0 or greater!');
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
        return $this->width;
    }

    /**
     * Sets the height.
     *
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        if ($height < 0.00) {
            throw new LayoutArgumentException('Element height must be 0 or greater!');
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
        return $this->height;
    }

    /**
     * Sets the unique identifier.
     *
     * @param string $id
     */
    public function setId(string $id): void
    {
        if ($id === '') {
            throw new LayoutArgumentException('ID cannot be an empty string!');
        }

        $this->id = $id;
    }

    /**
     * Gets the unique identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}