<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

/**
 * Class ElementFactory
 *
 * Creates and operates on the various Element types, allowing the boilerplate logic to be moved
 * out of the builder.
 *
 * @package PdfTemplater\Layout\Basic
 */
class ElementFactory
{

    /**
     * Creates an Element of the given type.
     *
     * @param string $type
     * @param string $id
     * @return Element
     */
    public function createElement(string $type, string $id): Element
    {
        return new Element($id);
    }

    /**
     * Extracts and sets the Element-specific extended attributes.
     *
     * @param Element  $element
     * @param string[] $getAttributes
     */
    public function setExtendedAttributes(Element $element, array $getAttributes): void
    {
    }
}