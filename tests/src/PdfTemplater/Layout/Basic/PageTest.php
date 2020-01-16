<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{

    public function testGetWidth()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->assertSame(1.0, $test->getWidth());

        $test->setWidth(100.0);

        $this->assertSame(100.0, $test->getWidth());
    }

    public function testSetNumber()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $test->setNumber(2);

        $this->assertSame(2, $test->getNumber());
    }

    public function testSetNumberInvalid1()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setNumber(0);
    }

    public function testSetNumberInvalid2()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setNumber(-2);
    }

    public function testBasic()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->assertSame(1, $test->getNumber());
    }

    public function testRemoveLayerByNumber()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $layer = new Layer(3);

        $test->addLayer($layer);

        $this->assertSame($layer, $test->getLayer(3));

        $test->removeLayerByNumber(3);

        $this->assertNull($test->getLayer(3));
    }

    public function testGetNumber()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->assertSame(1, $test->getNumber());

        $test->setNumber(2);

        $this->assertSame(2, $test->getNumber());
    }

    public function testRemoveLayer()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $layer = new Layer(3);

        $test->addLayer($layer);

        $this->assertSame($layer, $test->getLayer(3));

        $test->removeLayer($layer);

        $this->assertNull($test->getLayer(3));
    }

    public function testGetLayers()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->assertSame([], $test->getLayers());

        $layer1 = new Layer(3);
        $layer2 = new Layer(4);

        $test->addLayer($layer1);
        $test->addLayer($layer2);

        $layers = $test->getLayers();

        $this->assertContains($layer1, $layers, '', false, true);
        $this->assertContains($layer2, $layers, '', false, true);
    }

    public function testAddLayer()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $layer1 = new Layer(3);
        $layer2 = new Layer(4);
        $layer3 = new Layer(4);

        $test->addLayer($layer1);
        $test->addLayer($layer2);

        $this->assertSame($layer1, $test->getLayer(3));
        $this->assertSame($layer2, $test->getLayer(4));

        $test->addLayer($layer3);

        $this->assertSame($layer3, $test->getLayer(4));
    }

    public function testGetLayer()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $layer1 = new Layer(3);
        $layer2 = new Layer(4);

        $test->addLayer($layer1);
        $test->addLayer($layer2);

        $this->assertSame($layer1, $test->getLayer(3));
        $this->assertSame($layer2, $test->getLayer(4));
        $this->assertNull($test->getLayer(5));
    }

    public function testSetLayers()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $layer1 = new Layer(3);
        $layer2 = new Layer(4);
        $layer3 = new Layer(5);

        $test->setLayers([$layer1, $layer2]);

        $this->assertSame($layer1, $test->getLayer(3));
        $this->assertSame($layer2, $test->getLayer(4));
        $this->assertNull($test->getLayer(5));

        $test->setLayers([$layer3]);

        $this->assertSame($layer1, $test->getLayer(3));
        $this->assertSame($layer2, $test->getLayer(4));
        $this->assertSame($layer3, $test->getLayer(5));
    }

    public function testSetHeight()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $test->setHeight(100.0);

        $this->assertSame(100.0, $test->getHeight());
    }

    public function testSetHeightInvalid1()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setHeight(0.0);
    }

    public function testSetHeightInvalid2()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setHeight(-100.0);
    }

    public function testSetWidth()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $test->setWidth(100.0);

        $this->assertSame(100.0, $test->getWidth());
    }

    public function testSetWidthInvalid1()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setWidth(0.0);
    }

    public function testSetWidthInvalid2()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->expectException(LayoutArgumentException::class);

        $test->setWidth(-100.0);
    }

    public function testGetHeight()
    {
        $test = new Page(1, 1.0, 1.0, []);

        $this->assertSame(1.0, $test->getHeight());

        $test->setHeight(100.0);

        $this->assertSame(100.0, $test->getHeight());
    }
}
