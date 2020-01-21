<?php


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Font;
use PdfTemplater\Node\Node;

/**
 * Class FontBuilder
 *
 * Helper class for building Fonts.
 *
 * @package PdfTemplater\Builder\Basic
 */
class FontBuilder
{
    /**
     * Builds a Font from a Node.
     *
     * @param Node $fontNode
     * @return Font
     */
    public function buildFont(Node $fontNode): Font
    {
        $fontfile = $fontNode->getAttribute('file');
        $name = $fontNode->getAttribute('name');

        if (!$fontfile) {
            if (!\in_array(\strtolower($name), ['helvetica', 'courier', 'symbol', 'times', 'zapfdingbats'], true)) {
                throw new BuildException('Font file must be supplied for a non-core font.');
            } else {
                $fontfile = null;
            }
        } elseif (!\is_readable($fontfile) || !\is_file($fontfile)) {
            throw new BuildException('Cannot read font file: [' . $fontfile . ']');
        }

        $style = null;

        switch (\strtoupper((string)$fontNode->getAttribute('style'))) {
            case 'B':
            case 'BOLD':
            case Font::STYLE_BOLD:
                $style = Font::STYLE_BOLD;
                break;
            case 'I':
            case 'ITALIC':
            case Font::STYLE_ITALIC:
                $style = Font::STYLE_ITALIC;
                break;
            case 'BI':
            case 'IB':
            case 'BOLDITALIC':
            case 'ITALICBOLD':
            case Font::STYLE_BOLD_ITALIC:
                $style = Font::STYLE_BOLD_ITALIC;
                break;
            case '':
            case Font::STYLE_NORMAL:
                $style = Font::STYLE_NORMAL;
                break;
            default:
                throw new BuildException('Invalid font style.');
                break;
        }

        return new Font($name, $style, $fontfile);
    }
}