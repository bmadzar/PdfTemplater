<?php
declare(strict_types=1);

namespace PdfTemplater\Parser\Xml;


use PdfTemplater\Node\Basic\Node;
use PdfTemplater\Node\Node as NodeInterface;
use PdfTemplater\Node\Validator\IdValidator;
use PdfTemplater\Parser\ParseLogicException;
use PdfTemplater\Parser\Parser as ParserInterface;
use PdfTemplater\Parser\ParseSyntaxException;

/**
 * Class Parser
 *
 * Parses a compatible XML string into a Node tree.
 *
 * @package PdfTemplater\Parser\Xml
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
        \libxml_use_internal_errors(true);

        $xmlTree = new \DOMDocument();
        $xmlTree->loadXML($data, \LIBXML_COMPACT | \LIBXML_NONET);

        if ($err = \libxml_get_last_error()) {
            \libxml_use_internal_errors(false);

            throw new ParseSyntaxException($err->message, $err->code);
        }

        if (!$xmlTree->validate()) {
            \libxml_use_internal_errors(false);

            throw new ParseLogicException('Failed DTD validation!');
        }

        \libxml_use_internal_errors(false);

        $nodeTree = $this->buildDocument($xmlTree->documentElement);

        $validator = new IdValidator();

        if (!$validator->validate($nodeTree)) {
            throw new ParseLogicException('Duplicate ID found in tree!');
        }

        return $nodeTree;
    }

    /**
     * Builds a Node tree for a document.
     *
     * @param \DOMElement $element
     * @return NodeInterface
     */
    private function buildDocument(\DOMElement $element): NodeInterface
    {
        if ($element->tagName !== 'Document') {
            throw new ParseLogicException('Document element has incorrect tag name!');
        }

        $node = new Node(\strtolower($element->tagName));

        /** @var \DOMAttr $attribute */
        foreach ($element->attributes as $attribute) {
            $node->setAttribute(\strtolower($attribute->name), $attribute->value);
        }
        unset($attribute);

        foreach ($element->getElementsByTagName('Page') as $pageElement) {
            $node->addChild($this->buildPage($pageElement));
        }
        unset($pageElement);

        return $node;
    }

    /**
     * Builds a Node tree for a page.
     *
     * @param \DOMElement $element
     * @return NodeInterface
     */
    private function buildPage(\DOMElement $element): NodeInterface
    {
        if ($element->tagName !== 'Page') {
            throw new ParseLogicException('Page element has incorrect tag name!');
        }

        $node = new Node(\strtolower($element->tagName));

        /** @var \DOMAttr $attribute */
        foreach ($element->attributes as $attribute) {
            if (\strtolower($attribute->name) === 'id') {
                $node->setId($attribute->value);
            } else {
                $node->setAttribute(\strtolower($attribute->name), $attribute->value);
            }
        }
        unset($attribute);

        /** @var \DOMNode $elementElement */
        foreach ($element->childNodes as $elementElement) {
            if ($elementElement->nodeType === \XML_ELEMENT_NODE) {
                /** @var \DOMElement $elementElement */
                $node->addChild($this->buildElement($elementElement));
            }
        }
        unset($elementElement);

        return $node;
    }

    /**
     * Builds a Node for an element. Elements should not have children.
     *
     * @param \DOMElement $element
     * @return NodeInterface
     */
    private function buildElement(\DOMElement $element): NodeInterface
    {
        $node = new Node(\strtolower($element->tagName));

        /** @var \DOMAttr $attribute */
        foreach ($element->attributes as $attribute) {
            if (\strtolower($attribute->name) === 'id') {
                $node->setId($attribute->value);
            } else {
                $node->setAttribute(\strtolower($attribute->name), $attribute->value);
            }
        }
        unset($attribute);

        $node->setAttribute('content', $element->textContent);

        return $node;
    }
}