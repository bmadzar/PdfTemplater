<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class BookmarkElementTest extends TestCase
{

    public function testGetName()
    {
        $test = new BookmarkElement('test');

        $test->setName('test2');

        $this->assertSame('test2', $test->getName());
    }

    public function testGetNameUnset()
    {
        $test = new BookmarkElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->getName();
    }

    public function testBasic()
    {
        $test = new BookmarkElement('test');

        $this->assertSame('test', $test->getId());
        $this->assertSame(0, $test->getLevel());
    }

    public function testGetLevel()
    {
        $test = new BookmarkElement('test');

        $test->setLevel(1);

        $this->assertSame(1, $test->getLevel());
    }

    public function testSetLevel()
    {
        $test = new BookmarkElement('test');

        $test->setLevel(1);

        $this->assertSame(1, $test->getLevel());
    }

    public function testSetLevelInvalid()
    {
        $test = new BookmarkElement('test');

        $this->expectException(LayoutArgumentException::class);

        $test->setLevel(-1);
    }

    public function testIsValid()
    {
        $test = new BookmarkElement('test');

        $this->assertFalse($test->isValid());

        $test->setLevel(1);

        $this->assertFalse($test->isValid());

        $test->setName('test2');

        $this->assertTrue($test->isValid());
    }

    public function testSetName()
    {
        $test = new BookmarkElement('test');

        $test->setName('test2');

        $this->assertSame('test2', $test->getName());
    }
}
