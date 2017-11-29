<?php
declare(strict_types=1);

namespace PdfTemplater\Parser;


use PdfTemplater\Node\Node;

/**
 * Class JsonParser
 *
 * Parses a compatible JSON string into a Node tree.
 *
 * @package PdfTemplater\Parser
 */
class JsonParser implements Parser
{
    /**
     * Parses the input data stream into a tree of Nodes.
     *
     * @param string $data The input data to parse.
     * @return Node The Node tree obtained from parsing $data.
     */
    public function parse(string $data): Node
    {
        $jsonTree = \json_decode($data, true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new ParseSyntaxException('Could not parse JSON. [ ' . \json_last_error_msg() . ' ]', \json_last_error());
        }

        return $this->buildDocument($jsonTree);
    }

    /**
     * Builds a Node tree for a document.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildDocument(array $subtree): Node
    {

    }

    /**
     * Builds a Node tree for a page.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildPage(array $subtree): Node
    {

    }

    /**
     * Builds a Node for an element. Elements should not have children.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildElement(array $subtree): Node
    {

    }
}