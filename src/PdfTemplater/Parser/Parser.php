<?php
declare(strict_types=1);

namespace PdfTemplater\Parser;


use PdfTemplater\Node\Node;

/**
 * Interface Parser
 *
 * This interface needs to be implemented by all Parsers.
 * The role of the Parser is to take the string representation (e.g. JSON or XML) and
 * parse it into a common internal representation.
 *
 * @package PdfTemplater\Parser
 */
interface Parser
{
    /**
     * Parses the input data stream into a tree of Nodes.
     *
     * @param string $data The input data to parse.
     * @return Node The Node tree obtained from parsing $data.
     */
    public function parse(string $data): Node;
}