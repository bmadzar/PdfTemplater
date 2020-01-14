<?php
/**
 * Created by PhpStorm.
 * User: boris
 * Date: 2018-12-22
 * Time: 08:10
 */

namespace PdfTemplater\Parser\Json;

use PdfTemplater\Node\Node;
use PdfTemplater\Parser\ParseLogicException;
use PdfTemplater\Parser\ParseSyntaxException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private const DATA_FILE_PATH = __DIR__ . '/../../../../data/json_parser_tests';

    private function readDataFile(string $dataFile): string
    {
        $filename = self::DATA_FILE_PATH . '/' . $dataFile;

        if (!\is_readable($filename) || !\is_file($filename)) {
            $this->markTestSkipped(\sprintf('Cannot read data file: [%s]', $dataFile));
        }

        $data = \file_get_contents($filename);

        if ($data === false) {
            $this->markTestSkipped(\sprintf('Error reading data file: [%s]', $dataFile));
        }

        return $data;
    }

    public function testParseEmptyDocument()
    {
        $data = $this->readDataFile('empty_document.json');

        $parser = new Parser();

        $nodeTree = $parser->parse($data);

        $this->assertInstanceOf(Node::class, $nodeTree);
        $this->assertSame([], $nodeTree->getChildren());
        $this->assertSame([], $nodeTree->getAttributes());
    }

    public function testMalformedData()
    {
        $data = $this->readDataFile('malformed_data.txt');

        $parser = new Parser();

        $this->expectException(ParseSyntaxException::class);

        $parser->parse($data);
    }

    public function testOneBlankPage()
    {
        $data = $this->readDataFile('one_blank_page.json');

        $parser = new Parser();

        $nodeTree = $parser->parse($data);

        $this->assertInstanceOf(Node::class, $nodeTree);
        $this->assertSame([], $nodeTree->getAttributes());

        $this->assertCount(1, $nodeTree->getChildren());
        $this->assertNotEmpty($nodeTree->getChildById('page1'));

        $this->assertSame([], $nodeTree->getChildById('page1')->getChildren());
        $this->assertSame([], $nodeTree->getChildById('page1')->getAttributes());
    }


    public function testElementNoType()
    {
        $data = $this->readDataFile('element_no_type.json');

        $parser = new Parser();

        $this->expectException(ParseLogicException::class);

        $parser->parse($data);
    }

    public function testAttributes()
    {
        $data = $this->readDataFile('attributes.json');

        $parser = new Parser();

        $nodeTree = $parser->parse($data);

        $this->assertNotEmpty($nodeTree->getChildById('page1'));
        $this->assertNotEmpty($nodeTree->getChildById('el1'));

        $this->assertEquals(['docAttr1' => 'docAttr1Val', 'docAttr2' => 'docAttr2Val'], $nodeTree->getAttributes());
        $this->assertEquals(['pageAttr1' => 'pageAttr1Val', 'pageAttr2' => 'pageAttr2Val'], $nodeTree->getChildById('page1')->getAttributes());
        $this->assertEquals(['elAttr1' => 'elAttr1Val', 'elAttr2' => 'elAttr2Val'], $nodeTree->getChildById('el1')->getAttributes());
    }

    public function testDuplicateId()
    {
        $data = $this->readDataFile('duplicate_id.json');

        $parser = new Parser();

        $this->expectException(ParseLogicException::class);

        $parser->parse($data);
    }
}
