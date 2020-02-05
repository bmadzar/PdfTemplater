<?php
declare(strict_types=1);

namespace Renderer\Tcpdf;

use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Layout\Basic\RectangleElement;
use PdfTemplater\Layout\Basic\RgbColor;
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

        // PDFs will always have at least one default page
        $this->assertCount(1, $pdf->getPages());
        $this->assertCount(2, $pdf->getFonts());
    }

    public function testRender2()
    {
        $test = new Renderer();
        $doc = new Document();
        $page = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer = new Layer(0);
        $element = new RectangleElement('test', 2.0 * 72, 3.0 * 72, 1.5 * 72, 3.5 * 72, new RgbColor(0.0, 0.0, 0.0), 36.0, new RgbColor(1.0, 0.5, 0.0));

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $pdfPage = $pages[0];

        $obj = $pdfPage->get('Contents');

        $objContent = $obj->getContent();

    }
}
