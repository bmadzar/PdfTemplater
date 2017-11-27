<?php

namespace PdfTemplater\Renderer;


use PdfTemplater\Layout\Document;

/**
 * Class TcpdfRenderer
 *
 * Renders a PDF using TCPDF. Requires TCPDF be included in the autoload path.
 *
 * @package PdfTemplater\Renderer
 */
class TcpdfRenderer implements Renderer
{
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
     * Common method that backs all the public render*() methods.
     *
     * @param Document $document
     * @return \TCPDF
     */
    private function renderCommon(Document $document): \TCPDF
    {
        $pdf = new \TCPDF();

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