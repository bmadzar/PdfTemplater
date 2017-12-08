<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;


class BasicLayer implements Layer
{

    /**
     * @var int The Layer number (z-index).
     */
    private $number;

    /**
     * @var Element[] The element collection for this layer.
     */
    private $elements;

    /**
     * Layer constructor.
     *
     * Sets the Layer number, i.e. the z-index.
     *
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;

        $this->elements = [];
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
                throw new LayoutArgumentException('Invalid Element supplied!');
            }
        }
        unset($element);
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