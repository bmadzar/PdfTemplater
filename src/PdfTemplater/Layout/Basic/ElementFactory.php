<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\Color;
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
                $element->setColor(null);
            }

        }

        if ($element instanceof RectangleElement) {
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
        }

        if ($element instanceof LineElement) {
            if (isset($attributes['linewidth'])) {
                if (!\is_numeric($attributes['linewidth'])) {
                    throw new LayoutArgumentException('Invalid attribute value!');
                }

                $element->setStrokeWidth((float)$attributes['linewidth']);
            } else {
                $element->setStrokeWidth(null);
            }

            if (isset($attributes['linecolor'])) {
                $element->setFill($this->toColor($attributes['linecolor']));
            } else {
                $element->setFill(null);
            }
        }

        if ($element instanceof DataImageElement) {
            if (isset($attributes['data']) && $attributes['data']) {
                $element->setData($attributes['data']);
            } else {
                throw new LayoutArgumentException('Missing attribute!');
            }
        }

        if ($element instanceof FileImageElement) {
            if (isset($attributes['file']) && $attributes['file']) {
                $element->setFile($attributes['file']);
            } else {
                throw new LayoutArgumentException('Missing attribute!');
            }
        }

        if ($element instanceof EllipseElement) {
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
        }

        if ($element instanceof BookmarkElement) {
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
        }
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
        } elseif (\preg_match('/^\s*rgba\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new RgbColor((float)$matches[1] / 255, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[4] / 255);
        } elseif (\preg_match('/^\s*hsl\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new HslColor((float)$matches[1] / 360, (float)$matches[2] / 255, (float)$matches[3] / 255);
        } elseif (\preg_match('/^\s*hsla\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\,\s*([0-9]+)\s*\)\s*$/', $color, $matches)) {
            return new HslColor((float)$matches[1] / 360, (float)$matches[2] / 255, (float)$matches[3] / 255, (float)$matches[3] / 255);
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