<?php

namespace PdfTemplater\Builder\Basic;

use PHPUnit\Framework\TestCase;

class BoxTest extends TestCase
{

    public function testConstruct()
    {
        $box = new Box('test');

        $this->assertInstanceOf(Box::class, $box);
        $this->assertSame('test', $box->getId());
    }

    public function testId()
    {
        $box = new Box('test');

        $this->assertSame('test', $box->getId());

        $box->setId('test2');

        $this->assertSame('test2', $box->getId());
    }

    public function testTop()
    {
        $box = new Box('test');

        $this->assertNull($box->getTop());

        $box->setTop(1.0);

        $this->assertSame(1.0, $box->getTop());

        $box->setTop(-1.0);

        $this->assertSame(-1.0, $box->getTop());
    }

    public function testBottom()
    {
        $box = new Box('test');

        $this->assertNull($box->getBottom());

        $box->setBottom(1.0);

        $this->assertSame(1.0, $box->getBottom());

        $box->setBottom(-1.0);

        $this->assertSame(-1.0, $box->getBottom());
    }


    public function testLeft()
    {
        $box = new Box('test');

        $this->assertNull($box->getLeft());

        $box->setLeft(1.0);

        $this->assertSame(1.0, $box->getLeft());

        $box->setLeft(-1.0);

        $this->assertSame(-1.0, $box->getLeft());
    }

    public function testRight()
    {
        $box = new Box('test');

        $this->assertNull($box->getRight());

        $box->setRight(1.0);

        $this->assertSame(1.0, $box->getRight());

        $box->setRight(-1.0);

        $this->assertSame(-1.0, $box->getRight());
    }

    public function testWidth()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidth());

        $box->setWidth(1.0);

        $this->assertSame(1.0, $box->getWidth());

        $this->expectException(BoxArgumentException::class);

        $box->setWidth(-1.0);
    }

    public function testHeight()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeight());

        $box->setHeight(1.0);

        $this->assertSame(1.0, $box->getHeight());

        $this->expectException(BoxArgumentException::class);

        $box->setHeight(-1.0);
    }

    public function testWidthPercentage()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidthPercentage());

        $box->setWidthPercentage(1.0);

        $this->assertSame(1.0, $box->getWidthPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setWidthPercentage(-1.0);
    }

    public function testHeightPercentage()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeightPercentage());

        $box->setHeightPercentage(1.0);

        $this->assertSame(1.0, $box->getHeightPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setHeightPercentage(-1.0);
    }

    public function testTopRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getTopRelative());

        $box->setTopRelative('test2');

        $this->assertSame('test2', $box->getTopRelative());

        $this->expectException(ConstraintException::class);

        $box->setTopRelative('test');
    }


    public function testBottomRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getBottomRelative());

        $box->setBottomRelative('test2');

        $this->assertSame('test2', $box->getBottomRelative());

        $this->expectException(ConstraintException::class);

        $box->setBottomRelative('test');
    }


    public function testLeftRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getLeftRelative());

        $box->setLeftRelative('test2');

        $this->assertSame('test2', $box->getLeftRelative());

        $this->expectException(ConstraintException::class);

        $box->setLeftRelative('test');
    }


    public function testRightRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getRightRelative());

        $box->setRightRelative('test2');

        $this->assertSame('test2', $box->getRightRelative());

        $this->expectException(ConstraintException::class);

        $box->setRightRelative('test');
    }


    public function testHeightRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeightRelative());

        $box->setHeightRelative('test2');

        $this->assertSame('test2', $box->getHeightRelative());
        
        $this->expectException(ConstraintException::class);

        $box->setHeightRelative('test');
    }
    
    public function testWidthRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidthRelative());

        $box->setWidthRelative('test2');

        $this->assertSame('test2', $box->getWidthRelative());

        $this->expectException(ConstraintException::class);

        $box->setWidthRelative('test');
    }

}
