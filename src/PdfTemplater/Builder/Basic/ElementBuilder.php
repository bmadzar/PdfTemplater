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
        $attributes = $elementNode->getAttributes();

        if (!isset($attributes['content'])) {
            throw new LayoutArgumentException('Missing content!');
        } else {
            $content = $attributes['content'];
        }

        if (!isset($attributes['font'], $attributes['fontsize']) || !\is_numeric($attributes['fontsize'])) {
            throw new LayoutArgumentException('Missing attribute!');
        } else {
            $font = $attributes['font'];
            $fontsize = (float)$attributes['fontsize'];
        }

        if (isset($attributes['wrap'])) {
            switch (\strtolower($attributes['wrap'])) {
                case 'none':
                case '':
                case TextElement::WRAP_NONE:
                    $wrap = TextElement::WRAP_NONE;
                    break;
                case 'hard':
                case TextElement::WRAP_HARD:
                    $wrap = TextElement::WRAP_HARD;
                    break;
                case 'soft':
                case TextElement::WRAP_SOFT:
                    $wrap = TextElement::WRAP_SOFT;
                    break;
                default:
                    throw new LayoutArgumentException('Invalid attribute value!');
            }
        } else {
            $wrap = TextElement::WRAP_NONE;
        }

        if (isset($attributes['align'])) {
            switch (\strtolower($attributes['align'])) {
                case 'left':
                case '':
                case TextElement::ALIGN_LEFT:
                    $align = TextElement::ALIGN_LEFT;
                    break;
                case 'right':
                case TextElement::ALIGN_RIGHT:
                    $align = TextElement::ALIGN_RIGHT;
                    break;
                case 'soft':
                case TextElement::ALIGN_CENTER:
                    $align = TextElement::ALIGN_CENTER;
                    break;
                case 'justify':
                case TextElement::ALIGN_JUSTIFY:
                    $align = TextElement::ALIGN_JUSTIFY;
                    break;
                default:
                    throw new LayoutArgumentException('Invalid attribute value!');
            }
        } else {
            $align = TextElement::ALIGN_LEFT;
        }

        if (isset($attributes['valign'])) {
            switch (\strtolower($attributes['valign'])) {
                case 'top':
                case '':
                case TextElement::VERTICAL_ALIGN_TOP:
                    $valign = TextElement::VERTICAL_ALIGN_TOP;
                    break;
                case 'middle':
                case TextElement::VERTICAL_ALIGN_MIDDLE:
                    $valign = TextElement::VERTICAL_ALIGN_MIDDLE;
                    break;
                case 'bottom':
                case TextElement::VERTICAL_ALIGN_BOTTOM:
                    $valign = TextElement::VERTICAL_ALIGN_BOTTOM;
                    break;
                default:
                    throw new LayoutArgumentException('Invalid attribute value!');
            }
        } else {
            $valign = TextElement::VERTICAL_ALIGN_TOP;
        }

        if (isset($attributes['linesize'])) {
            if (!\is_numeric($attributes['linesize'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $linesize = (float)$attributes['linesize'];
        } else {
            $linesize = $fontsize;
        }

        if (isset($attributes['color'])) {
            $color = $this->toColor($attributes['color']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        [$stroke, $strokewidth, $fill] = $this->extractRectangleAttributes($attributes);

        return new TextElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $stroke,
            $strokewidth,
            $fill,
            $content,
            $font,
            $color,
            $fontsize,
            $linesize,
            $wrap,
            $align,
            $valign
        );
    }

    private function buildLineElement(Node $elementNode): LineElement
    {
        $attributes = $elementNode->getAttributes();

        if (isset($attributes['linewidth'])) {
            if (!\is_numeric($attributes['linewidth'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $linewidth = (float)$attributes['linewidth'];
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        if (isset($attributes['linecolor'])) {
            $linecolor = $this->toColor($attributes['linecolor']);
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return new LineElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $linewidth,
            $linecolor
        );
    }

    private function buildRectangleElement(Node $elementNode): RectangleElement
    {
        $attributes = $elementNode->getAttributes();

        [$stroke, $strokewidth, $fill] = $this->extractRectangleAttributes($attributes);

        return new RectangleElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $stroke,
            $strokewidth,
            $fill
        );
    }

    private function buildDataImageElement(Node $elementNode): DataImageElement
    {
        $attributes = $elementNode->getAttributes();

        if (isset($attributes['alt']) && $attributes['alt']) {
            $alt = $attributes['alt'];
        } else {
            $alt = null;
        }

        if (isset($attributes['data']) && $attributes['data']) {
            $data = $attributes['data'];
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        [$stroke, $strokewidth, $fill] = $this->extractRectangleAttributes($attributes);

        return new DataImageElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $stroke,
            $strokewidth,
            $fill,
            $data,
            $alt
        );
    }

    private function buildFileImageElement(Node $elementNode): FileImageElement
    {
        $attributes = $elementNode->getAttributes();

        if (isset($attributes['alt']) && $attributes['alt']) {
            $alt = $attributes['alt'];
        } else {
            $alt = null;
        }

        if (isset($attributes['file']) && $attributes['file']) {
            $file = $attributes['file'];
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        [$stroke, $strokewidth, $fill] = $this->extractRectangleAttributes($attributes);

        return new FileImageElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $stroke,
            $strokewidth,
            $fill,
            $file,
            $alt
        );
    }

    private function buildEllipseElement(Node $elementNode): EllipseElement
    {
        $attributes = $elementNode->getAttributes();

        [$stroke, $strokewidth, $fill] = $this->extractRectangleAttributes($attributes);

        return new EllipseElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $stroke,
            $strokewidth,
            $fill
        );
    }

    private function buildBookmarkElement(Node $elementNode): BookmarkElement
    {
        $attributes = $elementNode->getAttributes();

        if (isset($attributes['level'])) {
            if (!\is_numeric($attributes['level'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $level = (int)$attributes['level'];
        } else {
            $level = 0;
        }

        if (isset($attributes['name']) && $attributes['name']) {
            $name = $attributes['name'];
        } else {
            throw new LayoutArgumentException('Missing attribute!');
        }

        return new BookmarkElement(
            $elementNode->getId(),
            (float)$elementNode->getAttribute('left'),
            (float)$elementNode->getAttribute('top'),
            (float)$elementNode->getAttribute('width'),
            (float)$elementNode->getAttribute('height'),
            $level,
            $name
        );
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

    /**
     * @param string[] $attributes
     * @return array
     */
    private function extractRectangleAttributes(array $attributes): array
    {
        if (isset($attributes['stroke'])) {
            $stroke = $this->toColor($attributes['stroke']);
        } else {
            $stroke = null;
        }

        if (isset($attributes['strokewidth'])) {
            if (!\is_numeric($attributes['strokewidth'])) {
                throw new LayoutArgumentException('Invalid attribute value!');
            }

            $strokewidth = (float)$attributes['strokewidth'];
        } else {
            $strokewidth = null;
        }

        if (isset($attributes['fill'])) {
            $fill = $this->toColor($attributes['fill']);
        } else {
            $fill = null;
        }

        return [$stroke, $strokewidth, $fill];
    }
}