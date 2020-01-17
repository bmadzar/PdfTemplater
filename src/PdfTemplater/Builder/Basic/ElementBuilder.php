<?php


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\EllipseElement;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\Basic\LineElement;
use PdfTemplater\Layout\Basic\RectangleElement;
use PdfTemplater\Layout\Basic\TextElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Node\Node;

/**
 * Class ElementBuilder
 *
 * Helper class to build Elements.
 *
 * @package PdfTemplater\Builder\Basic
 */
class ElementBuilder
{
    /**
     * Builds an Element from a Node.
     *
     * @param Node $elementNode
     * @return Element
     */
    public function buildElement(Node $elementNode): Element
    {
        switch ($elementNode->getType()) {
            case 'text':
                return $this->buildTextElement($elementNode);
            case 'rectangle':
                return $this->buildRectangleElement($elementNode);
            case 'line':
                return $this->buildLineElement($elementNode);
            case 'image':
                return $this->buildDataImageElement($elementNode);
            case 'imagefile':
                return $this->buildFileImageElement($elementNode);
            case 'ellipse':
                return $this->buildEllipseElement($elementNode);
            case 'bookmark':
                return $this->buildBookmarkElement($elementNode);
            default:
                throw new LayoutArgumentException('Invalid Element type!');
        }
    }

    private function buildTextElement(Node $elementNode): TextElement
    {
    }

    private function buildLineElement(Node $elementNode): LineElement
    {

    }

    private function buildRectangleElement(Node $elementNode): RectangleElement
    {
    }

    private function buildDataImageElement(Node $elementNode): DataImageElement
    {

    }

    private function buildFileImageElement(Node $elementNode): FileImageElement
    {

    }

    private function buildEllipseElement(Node $elementNode): EllipseElement
    {
    }

    private function buildBookmarkElement(Node $elementNode): BookmarkElement
    {

    }
}