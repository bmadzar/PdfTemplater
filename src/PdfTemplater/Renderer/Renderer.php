<?php

namespace PdfTemplater\Renderer;


use PdfTemplater\Layout\Document;

/**
 * Interface Renderer
 *
 * This interface should be implemented by all Renderers.
 * The role of the Renderer is to take the final layout and produce the appropriate binary
 * output (e.g. PDF or PNG).
 *
 * @package PdfTemplater\Renderer
 */
interface Renderer
{
    /**
     * Renders the Document to a string.
     *
     * @param Document $document The Document to render.
     * @return string
     */
    public function render(Document $document): string;

    /**
     * Renders the Document to a file on the server.
     *
     * @param Document    $document The Document to render.
     * @param null|string $filename The optional filename to write.
     */
    public function renderToFile(Document $document, ?string $filename = null): void;

    /**
     * Renders the Document to the browser or to standard output, depending on SAPI.
     *
     * @param Document $document The Document to render.
     */
    public function renderToOutput(Document $document): void;
}