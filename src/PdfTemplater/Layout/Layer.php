<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface Layer
 *
 * A Layer represents a set of Elements that should be drawn at the same depth.
 * There is no guarantee in what order overlapping Elements within a Layer will be drawn.
 *
 * @package PdfTemplater\Layout
 */
interface Layer
{
    /**
     * Layer constructor.
     *
     * Sets the Layer number, i.e. the z-index.
     *
     * @param int $number
     */
    public function __construct(int $number);

    /**
     * Sets the full set of Elements. Elements need not be indexed in any particular way.
     *
     * @param Element[] $elements
     */
    public function setElements(array $elements): void;

    /**
     * Clears the current set of Elements, and optionally sets new ones.
     * Elements need not be indexed in any particular way.
     *
     * @param Element[] $elements
     */
    public function resetElements(array $elements = []): void;

    /**
     * Gets the full set of Elements.
     *
     * @return Element[]
     */
    public function getElements(): array;

    /**
     * Adds an Element to the Element set.
     *
     * @param Element $element
     */
    public function addElement(Element $element): void;

    /**
     * Gets an Element by its ID.
     *
     * @param string $id
     * @return null|Element
     */
    public function getElement(string $id): ?Element;

    /**
     * Removes the Element from the Element set. Nothing should happen if the Element
     * is not in the Element set.
     *
     * @param Element $element
     */
    public function removeElement(Element $element): void;

    /**
     * Removes the Element with the given ID from the Element set. Nothing should happen if the Element
     * is not in the Element set.
     *
     * @param string $id
     */
    public function removeElementById(string $id): void;

    /**
     * Gets the layer number (z-index).
     *
     * @return int
     */
    public function getNumber(): int;

    /**
     * Sets the layer number (z-index).
     *
     * @param int $number
     */
    public function setNumber(int $number): void;
}