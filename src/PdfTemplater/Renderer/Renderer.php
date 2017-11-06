<?php

namespace PdfTemplater\Renderer;


use PdfTemplater\Layout\Document;

interface Renderer
{
    public function render(Document $document): string;

    public function renderToFile(Document $document, ?string $filename = null): void;

    public function renderToOutput(Document $document): void;
}