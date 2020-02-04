<?php
declare(strict_types=1);


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildArgumentException;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Font;
use PdfTemplater\Layout\LayoutArgumentException;
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
        if (\strtolower($fontNode->getType()) !== 'font') {
            throw new BuildException('Incorrect node type!');
        }

        $fontfile = $fontNode->getAttribute('file');
        $name     = $fontNode->getAttribute('name');

        if (!$fontfile) {
            if (!\in_array(\strtolower($name), ['helvetica', 'courier', 'symbol', 'times', 'zapfdingbats'], true)) {
                throw new BuildArgumentException('Font file must be supplied for a non-core font.');
            } else {
                $fontfile = null;
            }
        } elseif (!\is_readable($fontfile) || !\is_file($fontfile)) {
            throw new BuildException('Cannot read font file: [' . $fontfile . ']');
        }

        if ($fontNode->getAttribute('style') === null) {
            throw new BuildArgumentException('Missing attribute!');
        }

        $style = null;

        switch (\strtoupper($fontNode->getAttribute('style'))) {
            case 'B':
            case 'BOLD':
            case (string)Font::STYLE_BOLD:
                $style = Font::STYLE_BOLD;
                break;
            case 'I':
            case 'ITALIC':
            case (string)Font::STYLE_ITALIC:
                $style = Font::STYLE_ITALIC;
                break;
            case 'BI':
            case 'IB':
            case 'BOLDITALIC':
            case 'ITALICBOLD':
            case (string)Font::STYLE_BOLD_ITALIC:
                $style = Font::STYLE_BOLD_ITALIC;
                break;
            case '':
            case (string)Font::STYLE_NORMAL:
                $style = Font::STYLE_NORMAL;
                break;
            default:
                throw new BuildArgumentException('Invalid font style.');
                break;
        }

        try {
            return new Font($name, $style, $fontfile);
        } catch (LayoutArgumentException $ex) {
            throw new BuildException('Invalid parameters provided to Font constructor.', 0, $ex);
        }
    }
}