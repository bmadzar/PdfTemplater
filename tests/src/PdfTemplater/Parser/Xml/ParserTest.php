<?php
/**
 * Created by PhpStorm.
 * User: boris
 * Date: 2018-12-22
 * Time: 08:11
 */

namespace PdfTemplater\Parser\Xml;

use PdfTemplater\Node\Node;
use PdfTemplater\Parser\ParseLogicException;
use PdfTemplater\Parser\ParseSyntaxException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private const DATA_FILE_PATH = __DIR__ . '/../../../../data/xml_parser_tests';

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
        $data = $this->readDataFile('empty_document.xml');

        $parser = new Parser();

        $nodeTree = $parser->parse($data);

        $this->assertInstanceOf(Node::class, $nodeTree);
        $this->assertSame([], $nodeTree->getChildren());
        $this->assertSame(['title' => 'test', 'description' => 'test'], $nodeTree->getAttributes());
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
        $data = $this->readDataFile('one_blank_page.xml');

        $parser = new Parser();

        $nodeTree = $parser->parse($data);

        $this->assertInstanceOf(Node::class, $nodeTree);
        $this->assertSame(['title' => 'test', 'description' => 'test'], $nodeTree->getAttributes());

        $this->assertCount(1, $nodeTree->getChildren());
        $this->assertNotEmpty($nodeTree->getChildById('page1'));

        $this->assertSame([], $nodeTree->getChildById('page1')->getChildren());
        $this->assertSame(['height' => '100', 'width' => '100', 'number' => '1'], $nodeTree->getChildById('page1')->getAttributes());
    }

    public function testDuplicateId()
    {
        $data = $this->readDataFile('duplicate_id.txt');

        $parser = new Parser();

        $this->expectException(ParseLogicException::class);

        $parser->parse($data);
    }

}
