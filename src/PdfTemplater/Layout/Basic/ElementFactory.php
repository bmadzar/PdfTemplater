<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\LayoutArgumentException;

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
        switch ($type) {
            case 'text':
                return new TextElement($id);
            case 'rectangle':
                return new RectangleElement($id);
            case 'line':
                return new LineElement($id);
            case 'image':
                return new DataImageElement($id);
            case 'imagefile':
                return new FileImageElement($id);
            case 'ellipse':
                return new EllipseElement($id);
            case 'bookmark':
                return new BookmarkElement($id);
            default:
                throw new LayoutArgumentException('Invalid Element type!');
        }
    }

    /**
     * Extracts and sets the Element-specific extended attributes.
     *
     * @param Element  $element
     * @param string[] $attributes
     */
    public function setExtendedAttributes(Element $element, array $attributes): void
    {
        if ($element instanceof TextElement) {
            if (isset($attributes['content'])) {
                $element->setText($attributes['content']);
            } else {
                $element->setText('');
            }

            if (!isset($attributes['font'], $attributes['fontsize']) || !\is_numeric($attributes['fontsize'])) {
                throw new LayoutArgumentException('Missing attributes!');
            } else {
                $element->setFont($attributes['font']);
                $element->setFontSize((float)$attributes['fontsize']);
            }

            if (isset($attributes['wrap'])) {
                if (!\is_numeric($attributes['wrap'])) {
                    throw new LayoutArgumentException('Invalid attribute value!');
                }

                $element->setWrapMode((int)$attributes['wrap']);
            } else {
                $element->setWrapMode(TextElement::WRAP_NONE);
            }

            if (isset($attributes['align'])) {
                if (!\is_numeric($attributes['align'])) {
                    throw new LayoutArgumentException('Invalid attribute value!');
                }

                $element->setAlignMode((int)$attributes['align']);
            } else {
                $element->setAlignMode(TextElement::ALIGN_LEFT);
            }

            if (isset($attributes['valign'])) {
                if (!\is_numeric($attributes['valign'])) {
                    throw new LayoutArgumentException('Invalid attribute value!');
                }

                $element->setVerticalAlignMode((int)$attributes['valign']);
            } else {
                $element->setVerticalAlignMode(TextElement::VERTICAL_ALIGN_TOP);
            }

            if (isset($attributes['linesize'])) {
                if (!\is_numeric($attributes['linesize'])) {
                    throw new LayoutArgumentException('Invalid attribute value!');
                }

                $element->setLineSize((float)$attributes['linesize']);
            } else {
                $element->setLineSize($element->getFontSize());
            }

        }

        if ($element instanceof RectangleElement) {

        }

        if ($element instanceof LineElement) {

        }

        if ($element instanceof DataImageElement) {

        }

        if ($element instanceof FileImageElement) {

        }

        if ($element instanceof EllipseElement) {

        }

        if ($element instanceof BookmarkElement) {

        }
    }
}