<?php
declare(strict_types=1);

namespace PdfTemplater\Parser;


use PdfTemplater\Node\BasicNode;
use PdfTemplater\Node\Node;
use PdfTemplater\Node\Validator\IdValidator;

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

        $nodeTree = $this->buildDocument($jsonTree);

        $validator = new IdValidator();

        if (!$validator->validate($nodeTree)) {
            throw new ParseLogicException('Duplicate ID found in tree!');
        }

        return $nodeTree;
    }

    /**
     * Builds a Node tree for a document.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildDocument(array $subtree): Node
    {
        $node = new BasicNode('document');

        foreach ($subtree['pages'] ?? [] as $id => $page) {
            $child = $this->buildPage($page);
            $child->setId($id);

            $node->addChild($child);
        }
        unset($id, $page, $child);

        foreach ($subtree as $key => $value) {
            if ($key !== 'pages' && \is_scalar($value)) {
                $node->setAttribute($key, (string)$value);
            }
        }
        unset($key, $value);

        return $node;
    }

    /**
     * Builds a Node tree for a page.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildPage(array $subtree): Node
    {
        $node = new BasicNode('page');

        foreach ($subtree['elements'] ?? [] as $id => $element) {
            $child = $this->buildElement($element);
            $child->setId($id);

            $node->addChild($child);
        }
        unset($id, $element, $child);

        foreach ($subtree as $key => $value) {
            if ($key !== 'elements' && \is_scalar($value)) {
                $node->setAttribute($key, (string)$value);
            }
        }
        unset($key, $value);

        return $node;
    }

    /**
     * Builds a Node for an element. Elements should not have children.
     *
     * @param array $subtree
     * @return Node
     */
    private function buildElement(array $subtree): Node
    {
        if (!isset($subtree['type'])) {
            throw new ParseLogicException('No element type set!');
        }

        $node = new BasicNode($subtree['type']);

        foreach ($subtree as $key => $value) {
            if (\is_scalar($value)) {
                $node->setAttribute($key, (string)$value);
            }
        }
        unset($key, $value);

        return $node;
    }
}