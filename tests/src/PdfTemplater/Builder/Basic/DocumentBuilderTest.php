<?php

namespace Builder\Basic;

use PdfTemplater\Builder\Basic\DocumentBuilder;
use PdfTemplater\Layout\Document;
use PdfTemplater\Node\Basic\Node;
use PHPUnit\Framework\TestCase;

class DocumentBuilderTest extends TestCase
{

    public function testBuildDocument1()
    {
        $test = new DocumentBuilder();

        $node = new Node('document', null, [
            'title' => 'Test Document',
        ]);

        $doc = $test->buildDocument($node);

        $this->assertInstanceOf(Document::class, $doc);
        $this->assertSame('title', $doc->getMetadataValue('title'));
    }
}
