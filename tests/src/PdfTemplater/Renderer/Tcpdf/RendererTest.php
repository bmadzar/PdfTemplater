<?php
declare(strict_types=1);

namespace Renderer\Tcpdf;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Basic\EllipseElement;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\LineElement;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Layout\Basic\RectangleElement;
use PdfTemplater\Layout\Basic\RgbColor;
use PdfTemplater\Layout\Basic\TextElement;
use PdfTemplater\Renderer\Tcpdf\Renderer;
use PHPUnit\Framework\TestCase;
use Smalot\PdfParser\Document as ParserDocument;
use Smalot\PdfParser\Parser;

class RendererTest extends TestCase
{
    private const DEBUG_SAVE = true;

    private function parsePdf(string $pdfData): ParserDocument
    {
        $header  = \substr($pdfData, 0, 8);
        $trailer = \substr(\rtrim($pdfData), -5);

        $this->assertRegExp('/^%PDF-\d\.\d$/', $header);
        $this->assertSame('%%EOF', $trailer);

        return (new Parser())->parseContent($pdfData);
    }

    private function debugSave(string $function, string $data)
    {
        if (!self::DEBUG_SAVE) {
            return;
        }

        static $dbgdir = null;
        static $ts = null;

        if ($dbgdir === null || $ts === null) {
            $dbgdir = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'pdftemplater_debug';
            $ts     = \time();
        }

        if (!\is_dir($dbgdir)) {
            if (!@\mkdir($dbgdir)) {
                $this->markTestIncomplete('Could not write debug data.');
            }
        }

        if (!\is_writable($dbgdir)) {
            $this->markTestIncomplete('Could not write debug data.');
        }

        if (\file_put_contents($dbgdir . \DIRECTORY_SEPARATOR . $ts . '-' . $function . '.pdf', $data) === false) {
            $this->markTestIncomplete('Could not write debug data.');
        }
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

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        // PDFs will always have at least one default page
        $this->assertCount(1, $pdf->getPages());
        $this->assertCount(1, $pdf->getFonts());
    }

    public function testRender2()
    {
        $test = new Renderer();

        $doc   = new Document();
        $page1 = new Page(1, 10.0, 10.0, []);
        $page2 = new Page(2, 10.0, 10.0, []);

        $doc->addPage($page1);
        $doc->addPage($page2);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $this->assertCount(2, $pdf->getPages());
    }

    public function testRender3()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new RectangleElement('test', 1.0 * 72, 4.0 * 72, 1.5 * 72, 0.5 * 72, new RgbColor(0.0, 0.0, 0.0), 18.0, new RgbColor(1.0, 0.5, 0.0));

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $pdfPage = $pages[0];

        $obj = $pdfPage->get('Contents');

        $objContent = $obj->getContent();

        $this->assertStringContainsString(
            '0.000000 0.500000 1.000000 0.000000 k',
            $objContent
        );

        $this->assertStringContainsString(
            '18.000000 w 0.000000 0.000000 0.000000 1.000000 K',
            $objContent
        );

        $this->assertStringContainsString(
            '72.000000 504.000000 108.000000 -36.000000 re B',
            $objContent
        );
    }

    public function testRender4()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new EllipseElement('test', 1.0 * 72, 4.0 * 72, 1.5 * 72, 0.5 * 72, new RgbColor(0.0, 0.0, 0.0), 18.0, new RgbColor(1.0, 0.5, 0.0));

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $pdfPage = $pages[0];

        $obj = $pdfPage->get('Contents');

        $objContent = $obj->getContent();

        $this->assertStringContainsString(
            '0.000000 0.500000 1.000000 0.000000 k',
            $objContent
        );

        $this->assertStringContainsString(
            '18.000000 w 0.000000 0.000000 0.000000 1.000000 K',
            $objContent
        );

        $this->assertStringContainsString(
            '180.000000 486.000000 m',
            $objContent
        );
    }

    public function testRender5()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new LineElement('test', 1.0 * 72, 4.0 * 72, 1.5 * 72, 0.5 * 72, 18.0, new RgbColor(1.0, 0.5, 0.0));

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $pdfPage = $pages[0];

        $obj = $pdfPage->get('Contents');

        $objContent = $obj->getContent();

        $this->assertStringContainsString(
            '18.000000 w 0.000000 0.500000 1.000000 0.000000 K',
            $objContent
        );

        $this->assertStringContainsString(
            '180.000000 468.000000 l',
            $objContent
        );
    }

    public function testRender6()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new BookmarkElement('test', 1.0 * 72, 4.0 * 72, 1.5 * 72, 0.5 * 72, 1, 'testName');

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $outlines = $pdf->getObjectsByType('Outlines');

        $this->assertCount(1, $outlines);

        // PDF Parser can't seem to parse outlines
    }

    public function testRender7()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new TextElement(
            'test',
            1.0 * 72,
            4.0 * 72,
            1.5 * 72,
            0.5 * 72,
            new RgbColor(0.0, 0.0, 0.0),
            18.0,
            new RgbColor(1.0, 0.5, 0.0),
            'test text',
            'Helvetica',
            new RgbColor(0.5, 0.5, 0.5),
            12.0,
            12.0,
            TextElement::WRAP_NONE,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $page = $pages[0];

        // PDF parser adds whitespace for some reason
        $this->assertSame('test text', \trim($page->getText()));
    }

    public function testRender8()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new FileImageElement(
            'test',
            1.0 * 72,
            4.0 * 72,
            1.5 * 72,
            0.5 * 72,
            new RgbColor(0.0, 0.0, 0.0),
            18.0,
            new RgbColor(1.0, 0.5, 0.0),
            __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
            'data' . \DIRECTORY_SEPARATOR . 'test_data' . \DIRECTORY_SEPARATOR . 'test_image.png',
            null
        );

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $images = $pdf->getObjectsByType('XObject', 'Image');

        $this->assertCount(1, $images);
    }

    public function testRender9()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new DataImageElement(
            'test',
            1.0 * 72,
            4.0 * 72,
            1.5 * 72,
            0.5 * 72,
            new RgbColor(0.0, 0.0, 0.0),
            18.0,
            new RgbColor(1.0, 0.5, 0.0),
            \base64_encode(\file_get_contents(
                __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
                'data' . \DIRECTORY_SEPARATOR . 'test_data' . \DIRECTORY_SEPARATOR . 'test_image.png'
            )),
            null
        );

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $images = $pdf->getObjectsByType('XObject', 'Image');

        $this->assertCount(1, $images);
    }

    public function testRender10()
    {
        $test    = new Renderer();
        $doc     = new Document();
        $page    = new Page(1, 8.5 * 72, 11.0 * 72);
        $layer   = new Layer(0);
        $element = new TextElement(
            'test',
            1.0 * 72,
            4.0 * 72,
            1.5 * 72,
            5.5 * 72,
            new RgbColor(0.0, 0.0, 0.0),
            18.0,
            new RgbColor(1.0, 0.5, 0.0),
            \str_repeat('test ', 100),
            'Helvetica',
            new RgbColor(0.5, 0.5, 0.5),
            12.0,
            12.0,
            TextElement::WRAP_HARD,
            TextElement::ALIGN_LEFT,
            TextElement::VERTICAL_ALIGN_TOP
        );

        $layer->addElement($element);
        $page->addLayer($layer);
        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $pages = $pdf->getPages();

        $this->assertCount(1, $pages);

        $page = $pages[0];

        $text = $page->getText();

        $this->assertSame(\trim(\str_repeat('test ', 100)), \trim(\strtr($text, "\n", " ")));
    }

    public function testRender11()
    {
        $test = new Renderer();
        $doc  = new Document([], [], [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);
        $page = new Page(1, 8.5 * 72, 11.0 * 72);

        $doc->addPage($page);

        $data = $test->render($doc);

        $this->debugSave(__FUNCTION__, $data);

        $pdf = $this->parsePdf($data);

        $details = $pdf->getDetails();

        $this->assertSame('Test Title', $details['Title']);
        $this->assertSame('Test Author', $details['Author']);
        $this->assertSame('kw1, kw2', $details['Keywords']);
        $this->assertSame('PdfTemplater', $details['Creator']);
        $this->assertSame('TCPDF', \substr($details['Producer'], 0, 5));
    }

}
