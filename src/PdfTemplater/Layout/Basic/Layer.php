<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Element;
use PdfTemplater\Layout\Layer as LayerInterface;

/**
 * Class Layer
 *
 * A basic implementation of a Layer.
 *
 * @package PdfTemplater\Layout\Basic
 */
class Layer implements LayerInterface
{

    /**
     * @var int The Layer number (z-index).
     */
    private int $number;

    /**
     * @var Element[] The element collection for this layer.
     */
    private array $elements;

    /**
     * Layer constructor.
     *
     * @param int       $number
     * @param Element[] $elements
     */
    public function __construct(int $number, array $elements = [])
    {
        $this->setNumber($number);
        $this->resetElements($elements);
    }

    /**
     * Sets the full set of Elements. Elements need not be indexed in any particular way.
     *
     * @param Element[] $elements
     */
    public function setElements(array $elements): void
    {
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                $this->addElement($element);
            } else {
                throw new \TypeError('Invalid Element supplied!');
            }
        }
        unset($element);
    }

    /**
     * Clears the current set of Elements, and optionally sets new ones.
     *
     * @param Element[] $elements
     */
    public function resetElements(array $elements = []): void
    {
        $this->elements = [];

        $this->setElements($elements);
    }

    /**
     * Gets the full set of Elements.
     *
     * @return Element[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * Adds an Element to the Element set.
     *
     * @param Element $element
     */
    public function addElement(Element $element): void
    {
        $this->elements[$element->getId()] = $element;
    }

    /**
     * Gets an Element by its ID.
     *
     * @param string $id
     * @return null|Element
     */
    public function getElement(string $id): ?Element
    {
        return $this->elements[$id] ?? null;
    }

    /**
     * Removes the Element from the Element set. Nothing should happen if the Element
     * is not in the Element set.
     *
     * @param Element $element
     */
    public function removeElement(Element $element): void
    {
        $this->removeElementById($element->getId());
    }

    /**
     * Removes the Element with the given ID from the Element set. Nothing should happen if the Element
     * is not in the Element set.
     *
     * @param string $id
     */
    public function removeElementById(string $id): void
    {
        unset($this->elements[$id]);
    }

    /**
     * Gets the layer number (z-index).
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Sets the layer number (z-index).
     *
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}