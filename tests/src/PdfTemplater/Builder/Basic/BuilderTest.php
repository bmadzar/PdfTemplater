<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\DataImageElement;
use PdfTemplater\Layout\Basic\FileImageElement;
use PdfTemplater\Layout\BookmarkElement;
use PdfTemplater\Layout\Document;
use PdfTemplater\Layout\EllipseElement;
use PdfTemplater\Layout\Layer;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\LineElement;
use PdfTemplater\Layout\Page;
use PdfTemplater\Layout\RectangleElement;
use PdfTemplater\Layout\TextElement;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testBuildEmptyDocument()
    {
        $test = new Builder();

        $node = new Node('document');

        $doc = $test->build($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertSame([], $doc->getPages());
    }

    public function testBuildInvalidRoot()
    {
        $test = new Builder();

        $node = new Node('page');

        $this->expectException(BuildException::class);

        $test->build($node);
    }

    public function testBuildBasic()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('rectangle', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '10',
            'left'   => '10',
        ]);

        $boxNode2 = new Node('rectangle', null, [
            'width'  => '200',
            'height' => '200',
            'top'    => '50',
            'left'   => '50',
        ]);

        $pageNode1->addChild($boxNode1);
        $pageNode1->addChild($boxNode2);

        $pageNode2 = new Node('page', null, [
            'width'  => '300',
            'height' => '400',
            'number' => '2',
        ]);

        $boxNode3 = new Node('ellipse', null, [
            'width'  => '350',
            'height' => '250',
            'bottom' => '5',
            'left'   => '3',
        ]);

        $pageNode2->addChild($boxNode3);

        $docNode->addChild($pageNode1);
        $docNode->addChild($pageNode2);

        $doc = $test->build($docNode);

        $this->assertSame('Test Title', $doc->getMetadataValue('title'));
        $this->assertSame('Test Author', $doc->getMetadataValue('author'));
        $this->assertSame('kw1, kw2', $doc->getMetadataValue('keywords'));

        $this->assertSame(2, \count($doc->getPages()));
        $this->assertInstanceOf(Page::class, $doc->getPage(1));
        $this->assertInstanceOf(Page::class, $doc->getPage(2));
        $this->assertNull($doc->getPage(3));

        $p1 = $doc->getPage(1);
        $p2 = $doc->getPage(2);

        $this->assertSame(100.0, $p1->getWidth());
        $this->assertSame(200.0, $p1->getHeight());

        $this->assertSame(300.0, $p2->getWidth());
        $this->assertSame(400.0, $p2->getHeight());

        $this->assertSame(1, \count($p1->getLayers()));
        $this->assertSame(1, \count($p2->getLayers()));

        $p1Els = $p1->getLayer(0)->getElements();
        $p2Els = $p2->getLayer(0)->getElements();

        $this->assertSame(2, \count($p1Els));
        $this->assertSame(1, \count($p2Els));

        /** @var EllipseElement $el */
        $el = reset($p2Els);

        $this->assertInstanceOf(EllipseElement::class, $el);
        $this->assertSame(350.0, $el->getWidth());
        $this->assertSame(250.0, $el->getHeight());
        $this->assertSame(-245.0, $el->getTop());
        $this->assertSame(3.0, $el->getLeft());
    }

    public function testBuildLayers()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        for ($layer = 0; $layer < 10; ++$layer) {
            $boxNode = new Node('rectangle', null, [
                'width'  => '100',
                'height' => '100',
                'top'    => '100',
                'left'   => '100',
                'layer'  => $layer * 2,
            ]);

            $boxNode->setId('l' . ($layer * 2));

            $pageNode1->addChild($boxNode);
        }
        unset($layer, $boxNode);

        $docNode->addChild($pageNode1);

        $doc = $test->build($docNode);

        $this->assertSame(1, \count($doc->getPages()));

        $p1 = $doc->getPage(1);

        $this->assertSame(10, \count($p1->getLayers()));

        for ($layerNum = 0; $layerNum < 10; ++$layerNum) {
            $layer = $p1->getLayer($layerNum * 2);

            $this->assertInstanceOf(Layer::class, $layer);
            $this->assertNull($p1->getLayer(($layerNum * 2) + 1));

            $this->assertSame(1, \count($layer->getElements()));

            $el = $layer->getElement('l' . ($layerNum * 2));

            $this->assertInstanceOf(RectangleElement::class, $el);

            $this->assertSame(100.0, $el->getWidth());
            $this->assertSame(100.0, $el->getHeight());
            $this->assertSame(100.0, $el->getTop());
            $this->assertSame(100.0, $el->getLeft());
        }
        unset($layerNum);
    }

    public function testBuildElements()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $attrs = [
            'width'  => '100',
            'height' => '100',
            'top'    => '100',
            'left'   => '100',
        ];

        $boxNode1 = new Node('bookmark', 'bookmark', $attrs + ['name' => 'bookmark']);
        $boxNode2 = new Node('ellipse', 'ellipse', $attrs + []);
        $boxNode3 = new Node('line', 'line', $attrs + ['linewidth' => 1.0, 'linecolor' => '#000000']);
        $boxNode4 = new Node('rectangle', 'rectangle', $attrs + []);
        $boxNode5 = new Node('text', 'text', $attrs + ['font' => 'Times', 'fontsize' => 12.0, 'color' => '#000000', 'content' => 'test content']);
        $boxNode6 = new Node('image', 'image', $attrs + ['data' => \base64_encode(\file_get_contents(__DIR__ . '/../../../../data/test_data/test_image.png'))]);
        $boxNode7 = new Node('imagefile', 'imagefile', $attrs + ['file' => __DIR__ . '/../../../../data/test_data/test_image.png']);

        $pageNode1->addChild($boxNode1);
        $pageNode1->addChild($boxNode2);
        $pageNode1->addChild($boxNode3);
        $pageNode1->addChild($boxNode4);
        $pageNode1->addChild($boxNode5);
        $pageNode1->addChild($boxNode6);
        $pageNode1->addChild($boxNode7);

        $docNode->addChild($pageNode1);

        $doc = $test->build($docNode);

        $page = $doc->getPage(1);

        $this->assertInstanceOf(Page::class, $page);

        $layer = $page->getLayer(0);

        $this->assertInstanceOf(Layer::class, $layer);

        $el1 = $layer->getElement('bookmark');
        $this->assertInstanceOf(BookmarkElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('ellipse');
        $this->assertInstanceOf(EllipseElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('line');
        $this->assertInstanceOf(LineElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('rectangle');
        $this->assertInstanceOf(RectangleElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('text');
        $this->assertInstanceOf(TextElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('image');
        $this->assertInstanceOf(DataImageElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());

        $el1 = $layer->getElement('imagefile');
        $this->assertInstanceOf(FileImageElement::class, $el1);
        $this->assertSame(100.0, $el1->getWidth());
        $this->assertSame(100.0, $el1->getHeight());
        $this->assertSame(100.0, $el1->getLeft());
        $this->assertSame(100.0, $el1->getTop());
    }

    public function testBuildInvalid1()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('bookmark', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '0',
            'left'   => '0',
        ]);

        $pageNode1->addChild($boxNode1);
        $docNode->addChild($pageNode1);

        $this->expectException(LayoutArgumentException::class);

        $test->build($docNode);
    }

    public function testBuildInvalid2()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('line', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '0',
            'left'   => '0',
        ]);

        $pageNode1->addChild($boxNode1);
        $docNode->addChild($pageNode1);

        $this->expectException(LayoutArgumentException::class);

        $test->build($docNode);
    }

    public function testBuildInvalid3()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('text', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '0',
            'left'   => '0',
        ]);

        $pageNode1->addChild($boxNode1);
        $docNode->addChild($pageNode1);

        $this->expectException(LayoutArgumentException::class);

        $test->build($docNode);
    }

    public function testBuildInvalid4()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('image', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '0',
            'left'   => '0',
        ]);

        $pageNode1->addChild($boxNode1);
        $docNode->addChild($pageNode1);

        $this->expectException(LayoutArgumentException::class);

        $test->build($docNode);
    }

    public function testBuildInvalid5()
    {
        $test = new Builder();

        $docNode = new Node('document', null, [
            'title'    => 'Test Title',
            'author'   => 'Test Author',
            'keywords' => 'kw1, kw2',
        ]);

        $pageNode1 = new Node('page', null, [
            'width'  => '100',
            'height' => '200',
            'number' => '1',
        ]);

        $boxNode1 = new Node('imagefile', null, [
            'width'  => '100',
            'height' => '100',
            'top'    => '0',
            'left'   => '0',
        ]);

        $pageNode1->addChild($boxNode1);
        $docNode->addChild($pageNode1);

        $this->expectException(LayoutArgumentException::class);

        $test->build($docNode);
    }
}
