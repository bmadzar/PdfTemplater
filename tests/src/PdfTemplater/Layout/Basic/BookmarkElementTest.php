<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PdfTemplater\Layout\LayoutArgumentException;
use PHPUnit\Framework\TestCase;

class BookmarkElementTest extends TestCase
{

    public function testGetName()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 0, 'test2');

        $this->assertSame('test2', $test->getName());
    }

    public function testBasic()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 0, 'testName');

        $this->assertSame('test', $test->getId());
        $this->assertSame(0, $test->getLevel());
    }

    public function testGetLevel()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 1, 'testName');

        $this->assertSame(1, $test->getLevel());
    }

    public function testSetLevel()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 2, 'testName');

        $test->setLevel(1);

        $this->assertSame(1, $test->getLevel());
    }

    public function testSetLevelInvalid()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 0, 'testName');

        $this->expectException(LayoutArgumentException::class);

        $test->setLevel(-1);
    }

    public function testSetName()
    {
        $test = new BookmarkElement('test', 0.0, 0.0, 1.0, 1.0, 0, 'test3');

        $test->setName('test2');

        $this->assertSame('test2', $test->getName());
    }
}
