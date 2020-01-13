<?php
declare(strict_types=1);

namespace PdfTemplater\Parser\Json;


use PdfTemplater\Node\Basic\Node;
use PdfTemplater\Node\Node as NodeInterface;
use PdfTemplater\Node\Validator\IdValidator;
use PdfTemplater\Parser\ParseLogicException;
use PdfTemplater\Parser\Parser as ParserInterface;
use PdfTemplater\Parser\ParseSyntaxException;

/**
 * Class Parser
 *
 * Parses a compatible JSON string into a Node tree.
 *
 * @package PdfTemplater\Parser\Json
 */
class Parser implements ParserInterface
{
    /**
     * Parses the input data stream into a tree of Nodes.
     *
     * @param string $data The input data to parse.
     * @return NodeInterface The Node tree obtained from parsing $data.
     */
    public function parse(string $data): NodeInterface
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
     * @return NodeInterface
     */
    private function buildDocument(array $subtree): NodeInterface
    {
        $node = new Node('document');

        foreach ($subtree['pages'] ?? [] as $id => $page) {
            $child = $this->buildPage($page);
            $child->setId((string)$id);

            $node->addChild($child);
        }
        unset($id, $page, $child);

        foreach ($subtree['fonts'] ?? [] as $id => $font) {
            $child = $this->buildFont($font);
            $child->setId((string)$id);

            $node->addChild($child);
        }
        unset($id, $page, $child);

        if (!empty($subtree['defaults']) && \is_array($subtree['defaults'])) {
            $child = $this->buildDefaults($subtree['defaults']);

            $node->addChild($child);
        }
        unset($child);

        foreach ($subtree as $key => $value) {
            if (!\in_array($key, ['pages', 'fonts', 'defaults'], true) && \is_scalar($value)) {
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
     * @return NodeInterface
     */
    private function buildPage(array $subtree): NodeInterface
    {
        $node = new Node('page');

        foreach ($subtree['elements'] ?? [] as $id => $element) {
            $child = $this->buildElement($element);
            $child->setId((string)$id);

            $node->addChild($child);
        }
        unset($id, $element, $child);

        if (!empty($subtree['defaults']) && \is_array($subtree['defaults'])) {
            $child = $this->buildDefaults($subtree['defaults']);

            $node->addChild($child);
        }
        unset($child);

        foreach ($subtree as $key => $value) {
            if (!\in_array($key, ['elements', 'defaults'], true) && \is_scalar($value)) {
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
     * @return NodeInterface
     */
    private function buildElement(array $subtree): NodeInterface
    {
        if (!isset($subtree['type'])) {
            throw new ParseLogicException('No element type set!');
        }

        $node = new Node($subtree['type']);

        foreach ($subtree as $key => $value) {
            if (\is_scalar($value) && $key !== 'type') {
                $node->setAttribute($key, (string)$value);
            }
        }
        unset($key, $value);

        return $node;
    }

    /**
     * Builds a Node for an font. Fonts should not have children.
     *
     * @param array $subtree
     * @return NodeInterface
     */
    private function buildFont(array $subtree): NodeInterface
    {
        $node = new Node('font');

        foreach ($subtree as $key => $value) {
            if (\is_scalar($value)) {
                $node->setAttribute($key, (string)$value);
            }
        }
        unset($key, $value);

        return $node;
    }

    private function buildDefaults(array $subtree): NodeInterface {

    }
}