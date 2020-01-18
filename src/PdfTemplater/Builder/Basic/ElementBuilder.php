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
        $element = new LineElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['linewidth'])) {
            if (!\is_numeric($attributes['linewidth'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $element->setLineWidth((float)$attributes['linewidth']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        if (isset($attributes['linecolor'])) {
            $element->setLineColor($this->toColor($attributes['linecolor']));
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return $attributes;
    }

    private function buildRectangleElement(Node $elementNode): RectangleElement
    {
        $element = new RectangleElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['strokewidth'])) {
            if (!\is_numeric($attributes['strokewidth'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $element->setStrokeWidth((float)$attributes['strokewidth']);
        } else {
            $element->setStrokeWidth(null);
        }

        if (isset($attributes['stroke'])) {
            $element->setStroke($this->toColor($attributes['stroke']));
        } else {
            $element->setStroke(null);
        }


        if (isset($attributes['fill'])) {
            $element->setFill($this->toColor($attributes['fill']));
        } else {
            $element->setFill(null);
        }

        return $element;
    }

    private function buildDataImageElement(Node $elementNode): DataImageElement
    {
        $element = new DataImageElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['alt']) && $attributes['alt']) {
            $element->setAltText($attributes['alt']);
        }

        if (isset($attributes['data']) && $attributes['data']) {
            $element->setData($attributes['data']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return $element;
    }

    private function buildFileImageElement(Node $elementNode): FileImageElement
    {
        $element = new FileImageElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['alt']) && $attributes['alt']) {
            $element->setAltText($attributes['alt']);
        }

        if (isset($attributes['file']) && $attributes['file']) {
            $element->setFile($attributes['file']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return $element;
    }

    private function buildEllipseElement(Node $elementNode): EllipseElement
    {
        $element = new EllipseElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['strokewidth'])) {
            if (!\is_numeric($attributes['strokewidth'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $element->setStrokeWidth((float)$attributes['strokewidth']);
        } else {
            $element->setStrokeWidth(null);
        }

        if (isset($attributes['stroke'])) {
            $element->setStroke($this->toColor($attributes['stroke']));
        } else {
            $element->setStroke(null);
        }


        if (isset($attributes['fill'])) {
            $element->setFill($this->toColor($attributes['fill']));
        } else {
            $element->setFill(null);
        }

        return $element;
    }

    private function buildBookmarkElement(Node $elementNode): BookmarkElement
    {
        $element = new BookmarkElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['level'])) {
            if (!\is_numeric($attributes['level'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $element->setLevel((int)$attributes['level']);
        } else {
            $element->setLevel(0);
        }

        if (isset($attributes['name']) && $attributes['name']) {
            $element->setName($attributes['name']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return $element;
    }
}