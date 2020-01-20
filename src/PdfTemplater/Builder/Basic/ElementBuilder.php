<?php


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PdfTemplater\Layout\Basic\CmykColor;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\EllipseElement;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\Basic\HslColor;
use PdfTemplater\Layout\Basic\LineElement;
use PdfTemplater\Layout\Basic\RectangleElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Basic\TextElement;
use PdfTemplater\Layout\Color;
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
        $element = new TextElement();

        $attributes = $elementNode->getAttributes();

        if (isset($attributes['content'])) {
            $element->setText($attributes['content']);
        } else {
            $element->setText('');
        }

        if (!isset($attributes['font'], $attributes['fontsize']) || !\is_numeric($attributes['fontsize'])) {
            throw new LayoutArgumentException('Missing attribute!');
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

        if (isset($attributes['color'])) {
            $element->setColor($this->toColor($attributes['color']));
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

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

    /**
     * Parses one of the standard color formats into a Color of the appropriate type.
     *
     * @param string $color
     * @return Color
     */
    private function toColor(string $color): Color
    {
        $matches = [];

        if (\preg_match('/^\s*#?\s*([0-9a-f]{3}|[0-9a-f]{6})$/i', $color, $matches)) {
            return RgbColor::createFromHex($matches[1]);
        } elseif (\preg_match('/^\s*rgb\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new RgbColor((float)$matches[1] / 255, (float)$matches[2] / 255, (float)$matches[3] / 255);
        } elseif (\preg_match('/^\s*rgba\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new RgbColor((float)$matches[1] / 255, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[4] / 255);
        } elseif (\preg_match('/^\s*hsl\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new HslColor((float)$matches[1] / 360, (float)$matches[2] / 255, (float)$matches[3] / 255);
        } elseif (\preg_match('/^\s*hsla\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new HslColor((float)$matches[1] / 360, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[4] / 255);
        } elseif (\preg_match('/^\s*cmyk\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new CmykColor((float)$matches[1] / 255, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[4] / 255);
        } elseif (\preg_match('/^\s*cmyka\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new CmykColor((float)$matches[1] / 255, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[4] / 255, (float)$matches[5] / 255);
        } else {

            switch (\trim(\strtolower($color))) {
                case 'blue':
                    return new RgbColor(0.0, 0.0, 1.0, 1.0);
                case 'red':
                    return new RgbColor(1.0, 0.0, 0.0, 1.0);
                case 'green':
                    return new RgbColor(0.0, 1.0, 0.0, 1.0);
                case 'yellow':
                    return new RgbColor(1.0, 1.0, 0.0, 1.0);
                case 'black':
                    return new RgbColor(0.0, 0.0, 0.0, 1.0);
                case 'white':
                    return new RgbColor(1.0, 1.0, 1.0, 1.0);
                case 'grey':
                case 'gray':
                    return new RgbColor(0.5, 0.5, 0.5, 1.0);
                case 'transparent':
                    return new RgbColor(1.0, 1.0, 1.0, 0.0);
                default:
                    throw new LayoutArgumentException('Invalid color value supplied!');
            }
        }
    }
}