<?php

namespace Renderer\Tcpdf;

use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Renderer\Tcpdf\Renderer;
use PHPUnit\Framework\TestCase;
use Smalot\PdfParser\Document as ParserDocument;
use Smalot\PdfParser\Parser;

class RendererTest extends TestCase
{
    private function parsePdf(string $pdfData): ParserDocument
    {
        $header = \substr($pdfData, 0, 8);
        $trailer = \substr(\rtrim($pdfData), -5);

        $this->assertRegExp('/^%PDF-\d\.\d$/', $header);
        $this->assertSame('%%EOF', $trailer);

        return (new Parser())->parseContent($pdfData);
    }

    public function testRenderToFile()
    {
        $test = new Renderer();

        $doc = new Document();

        $tmp = \tempnam(\sys_get_temp_dir(), 'pdf');

        $test->renderToFile($doc, $tmp);

        $this->assertIsReadable($tmp);

        $this->parsePdf(\file_get_contents($tmp));
    }

    public function testRenderToOutput()
    {
        $test = new Renderer();

        $doc = new Document();

        \ob_start();

        $test->renderToOutput($doc);

        $data = \ob_get_contents();

        \ob_end_clean();

        $this->parsePdf($data);
    }

    public function testPrerequisites()
    {
        $this->assertInstanceOf(\TCPDF::class, new \TCPDF());
    }

    public function testRender1()
    {
        $test = new Renderer();

        $doc = new Document();

        $data = $test->render($doc);

        $pdf = $this->parsePdf($data);

        $this->assertCount(0, $pdf->getPages());
        $this->assertCount(0, $pdf->getFonts());
    }
}
