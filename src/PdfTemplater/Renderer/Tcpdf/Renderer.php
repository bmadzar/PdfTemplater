<?php
declare(strict_types=1);

namespace PdfTemplater\Renderer\Tcpdf;


use PdfTemplater\Layout\Basic\LineElement;
use PdfTemplater\Layout\BookmarkElement;
use PdfTemplater\Layout\Document;
use PdfTemplater\Layout\Element;
use PdfTemplater\Layout\EllipseElement;
use PdfTemplater\Layout\ImageElement;
use PdfTemplater\Layout\Page;
use PdfTemplater\Layout\RectangleElement;
use PdfTemplater\Layout\TextElement;
use PdfTemplater\Renderer\RenderEnvironmentException;
use PdfTemplater\Renderer\Renderer as RendererInterface;
use PdfTemplater\Renderer\RenderInputException;
use PdfTemplater\Renderer\RenderProcessException;

/**
 * Class Renderer
 *
 * Renders a PDF using TCPDF. Requires TCPDF be included in the autoload path.
 *
 * @package PdfTemplater\RendererTcpdf
 */
class Renderer implements RendererInterface
{
    private const CREATOR = 'PdfTemplater';

    /**
     * Sets the TCPDF metadata. (e.g. title, author)
     *
     * @param Tcpdf    $pdf
     * @param Document $document
     */
    protected function setDocumentMetadata(Tcpdf $pdf, Document $document)
    {
        if ($document->hasMetadataValue('author')) {
            $pdf->SetAuthor($document->getMetadataValue('author'));
        }

        if ($document->hasMetadataValue('title')) {
            $pdf->SetTitle($document->getMetadataValue('title'));
        }

        if ($document->hasMetadataValue('keywords')) {
            $pdf->SetKeywords($document->getMetadataValue('keywords'));
        }

        $pdf->SetCreator(self::CREATOR);
    }

    /**
     * Renders an element.
     *
     * @param Tcpdf   $pdf
     * @param Element $element
     */
    protected function renderElement(Tcpdf $pdf, Element $element)
    {
        if ($element instanceof TextElement) {
            $this->renderTextElement($pdf, $element);
        } elseif ($element instanceof ImageElement) {
            $this->renderImageElement($pdf, $element);
        } elseif ($element instanceof RectangleElement) {
            $this->renderRectangleElement($pdf, $element);
        } elseif ($element instanceof EllipseElement) {
            $this->renderEllipseElement($pdf, $element);
        } elseif ($element instanceof LineElement) {
            $this->renderLineElement($pdf, $element);
        } elseif ($element instanceof BookmarkElement) {
            $this->renderBookmarkElement($pdf, $element);
        } else {
            throw new RenderProcessException('Renderer does not know how to draw this Element type!');
        }
    }

    /**
     * Renders a page.
     *
     * @param Tcpdf $pdf
     * @param Page  $page
     */
    protected function renderPage(Tcpdf $pdf, Page $page)
    {
        $pdf->AddPage('', [$page->getWidth(), $page->getHeight()], false, false);

        $layers = $page->getLayers();

        if (!\ksort($layers)) {
            throw new RenderProcessException('Sorting of layers failed!');
        }

        foreach ($layers as $layer) {
            foreach ($layer->getElements() as $element) {
                $this->renderElement($pdf, $element);
            }
            unset($element);
        }
        unset($layer);
    }

    /**
     * Common method that backs all the public render*() methods.
     *
     * @param Document $document
     * @return Tcpdf
     */
    protected function renderCommon(Document $document): Tcpdf
    {
        $pdf = new Tcpdf();

        $this->setDocumentMetadata($pdf, $document);

        $pages = $document->getPages();

        if (!\ksort($pages)) {
            throw new RenderProcessException('Sorting of pages failed!');
        }

        foreach ($pages as $page) {
            $this->renderPage($pdf, $page);
        }
        unset($pages, $page);

        return $pdf;
    }

    /**
     * Renders the Document and returns the representation as a string.
     *
     * @param Document $document
     * @return string
     */
    public function render(Document $document): string
    {
        $pdf = $this->renderCommon($document);

        return $pdf->Output('', 'S');
    }

    /**
     * Renders the Document and writes the results to either the filename supplied or the one
     * stored in the Document.
     *
     * @param Document    $document
     * @param null|string $filename
     */
    public function renderToFile(Document $document, ?string $filename = null): void
    {
        $pdf = $this->renderCommon($document);

        $filename = $filename ?? $document->getFilename();

        if (!$filename || !\is_writable($filename)) {
            throw new RenderInputException('Invalid filename supplied: ' . $filename);
        }

        $pdf->Output($filename, 'F');
    }

    /**
     * Renders the Document to either the browser or to the standard output, depending on SAPI.
     *
     * @param Document $document
     */
    public function renderToOutput(Document $document): void
    {
        $pdf = $this->renderCommon($document);

        $pdf->Output($document->getFilename() ?? '', 'I');
    }

    /**
     * Renders a rectangle.
     *
     * @param Tcpdf            $pdf
     * @param RectangleElement $element
     */
    private function renderRectangleElement(Tcpdf $pdf, RectangleElement $element)
    {
        $mode = '';
        $line = null;

        if ($element->getFill()) {
            $mode .= 'F';
        }

        if ($element->getStroke() && $element->getStrokeWidth()) {
            $mode .= 'D';

            $line = [
                'all' => [
                    'color' => $element->getStroke()->getCmyk(),
                    'width' => $element->getStrokeWidth(),
                ],
            ];
        }

        if (!$mode) {
            // Empty rectangle, no point in drawing it
            return;
        }

        $pdf->Rect(
            $element->getLeft(),
            $element->getTop(),
            $element->getWidth(),
            $element->getHeight(),
            $mode,
            $line ?: [],
            $element->getFill() ? $element->getFill()->getCmyk() : []
        );
    }

    /**
     * Renders an image.
     *
     * @param Tcpdf        $pdf
     * @param ImageElement $element
     */
    private function renderImageElement(Tcpdf $pdf, ImageElement $element)
    {
        $line = 0;

        if ($element->getStroke() && $element->getStrokeWidth()) {
            $line = [
                'LTRB' => [
                    'color' => $element->getStroke()->getCmyk(),
                    'width' => $element->getStrokeWidth(),
                ],
            ];
        }

        if ($element->getFill()) {
            $pdf->Rect(
                $element->getLeft(),
                $element->getTop(),
                $element->getWidth(),
                $element->getHeight(),
                'F',
                [],
                $element->getFill()->getCmyk()
            );
        }

        $pdf->Image(
            $element->getImageFile(),
            $element->getLeft(),
            $element->getTop(),
            $element->getWidth(),
            $element->getHeight(),
            '',
            '',
            '',
            true,
            300,
            '',
            false,
            false,
            $line,
            false,
            false,
            false,
            false,
            []
        );
    }

    /**
     * Renders text.
     * TCPDF cannot control the word wrapping mode, so that parameter has no effect.
     *
     * @param Tcpdf       $pdf
     * @param TextElement $element
     */
    private function renderTextElement(Tcpdf $pdf, TextElement $element)
    {
        if (!$element->getText()) {
            return; // No text, no point in drawing anything
        }

        $line = 0;

        if ($element->getStroke() && $element->getStrokeWidth()) {
            $line = [
                'LTRB' => [
                    'color' => $element->getStroke()->getCmyk(),
                    'width' => $element->getStrokeWidth(),
                ],
            ];
        }

        if ($element->getFill()) {
            $pdf->SetFillColorArray($element->getFill()->getCmyk());
        }

        if ($element->getWrapMode() === TextElement::WRAP_NONE) {
            $pdf->Text(
                $element->getLeft(),
                $element->getTop(),
                $element->getText(),
                false,
                false,
                true,
                $line,
                0,
                [
                    TextElement::ALIGN_LEFT    => 'L',
                    TextElement::ALIGN_CENTER  => 'C',
                    TextElement::ALIGN_RIGHT   => 'R',
                    TextElement::ALIGN_JUSTIFY => 'J',
                ][$element->getAlignMode()],
                $element->getFill() ? true : false,
                '',
                0,
                true,
                'T',
                [
                    TextElement::VERTICAL_ALIGN_TOP    => 'T',
                    TextElement::VERTICAL_ALIGN_MIDDLE => 'M',
                    TextElement::VERTICAL_ALIGN_BOTTOM => 'B',
                ][$element->getVerticalAlignMode()],
                false
            );
        } else {
            $pdf->MultiCell(
                $element->getWidth(),
                $element->getHeight(),
                $element->getText(),
                $line,
                [
                    TextElement::ALIGN_LEFT    => 'L',
                    TextElement::ALIGN_CENTER  => 'C',
                    TextElement::ALIGN_RIGHT   => 'R',
                    TextElement::ALIGN_JUSTIFY => 'J',
                ][$element->getAlignMode()],
                $element->getFill() ? true : false,
                0,
                $element->getLeft(),
                $element->getTop(),
                false,
                0,
                false,
                false,
                $element->getHeight(),
                [
                    TextElement::VERTICAL_ALIGN_TOP    => 'T',
                    TextElement::VERTICAL_ALIGN_MIDDLE => 'M',
                    TextElement::VERTICAL_ALIGN_BOTTOM => 'B',
                ][$element->getVerticalAlignMode()],
                false
            );
        }
    }

    /**
     * Renders an ellipse.
     *
     * @param Tcpdf          $pdf
     * @param EllipseElement $element
     */
    private function renderEllipseElement(Tcpdf $pdf, EllipseElement $element)
    {
        $mode = '';
        $line = null;

        if ($element->getFill()) {
            $mode .= 'F';
        }

        if ($element->getStroke() && $element->getStrokeWidth()) {
            $mode .= 'D';

            $line = [
                'all' => [
                    'color' => $element->getStroke()->getCmyk(),
                    'width' => $element->getStrokeWidth(),
                ],
            ];
        }

        if (!$mode) {
            // Empty ellipse, no point in drawing it
            return;
        }

        $pdf->Ellipse(
            $element->getLeft() + ($element->getWidth() / 2),
            $element->getTop() + ($element->getHeight() / 2),
            $element->getWidth() / 2,
            $element->getHeight() / 2,
            0,
            0,
            360,
            $mode,
            $line ?: [],
            $element->getFill() ? $element->getFill()->getCmyk() : [],
            2
        );
    }

    /**
     * Renders a line.
     *
     * @param Tcpdf       $pdf
     * @param LineElement $element
     */
    private function renderLineElement(Tcpdf $pdf, LineElement $element)
    {
        $line = [
            'all' => [
                'color' => $element->getLineColor()->getCmyk(),
                'width' => $element->getLineWidth(),
            ],
        ];

        $pdf->Line(
            $element->getLeft(),
            $element->getTop(),
            $element->getLeft() + $element->getWidth(),
            $element->getTop() + $element->getHeight(),
            $line
        );
    }

    /**
     * Renders a bookmark.
     *
     * @param Tcpdf           $pdf
     * @param BookmarkElement $element
     */
    private function renderBookmarkElement(Tcpdf $pdf, BookmarkElement $element)
    {
        $pdf->Bookmark(
            $element->getName(),
            $element->getLevel(),
            $element->getTop(),
            '',
            '',
            [0.0, 0.0, 0.0, 1.0],
            $element->getLeft(),
            ''
        );
    }
}