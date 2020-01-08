<?php

namespace Layout\Basic;

use PdfTemplater\Layout\Basic\BookmarkElement;
use PHPUnit\Framework\TestCase;

class BookmarkElementTest extends TestCase
{

    public function testGetName()
    {

    }

    public function testBasic()
    {
        $test = new BookmarkElement('test');

        $this->assertSame('test', $test->getId());
        $this->assertSame(0, $test->getLevel());
    }

    public function testGetLevel()
    {

    }

    public function testSetLevel()
    {

    }

    public function testIsValid()
    {

    }

    public function testSetName()
    {

    }
}