<?php
declare(strict_types=1);

namespace PdfTemplater\Renderer;


use PdfTemplater\Layout\Document;
use PdfTemplater\Layout\Element;
use PdfTemplater\Layout\Page;

/**
 * Class TcpdfRenderer
 *
 * Renders a PDF using TCPDF. Requires TCPDF be included in the autoload path.
 *
 * @package PdfTemplater\Renderer
 */
class TcpdfRenderer implements Renderer
{
    private const CREATOR = 'PdfTemplater';

    /**
     * TcpdfRenderer constructor.
     *
     * @throws RenderEnvironmentException Thrown if TCPDF is not found.
     */
    public function __construct()
    {
        if (!\class_exists(\TCPDF::class, true)) {
            throw new RenderEnvironmentException('TCPDF not found, cannot use TcpdfRenderer.');
        }
    }

    /**
     * Sets the TCPDF metadata. (e.g. title, author)
     *
     * @param \TCPDF   $pdf
     * @param Document $document
     */
    protected function setDocumentMetadata(\TCPDF $pdf, Document $document)
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
     * Sets the TCPDF document properties. (e.g. compression, protection)
     *
     * @param \TCPDF   $pdf
     */
    protected function setDocumentProperties(\TCPDF $pdf)
    {
        $pdf->SetCompression(true);

        $pdf->setPageUnit('pt');
    }

    /**
     * Renders an element.
     *
     * @param \TCPDF  $pdf
     * @param Element $element
     */
    protected function renderElement(\TCPDF $pdf, Element $element)
    {

    }

    /**
     * Renders a page.
     *
     * @param \TCPDF $pdf
     * @param Page   $page
     */
    protected function renderPage(\TCPDF $pdf, Page $page)
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
     * @return \TCPDF
     */
    protected function renderCommon(Document $document): \TCPDF
    {
        $pdf = new \TCPDF();

        $this->setDocumentProperties($pdf);
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
}