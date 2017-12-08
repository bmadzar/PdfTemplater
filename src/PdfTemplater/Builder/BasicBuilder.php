<?php
declare(strict_types=1);

namespace PdfTemplater\Builder;


use PdfTemplater\Layout\BasicDocument;
use PdfTemplater\Layout\BasicPage;
use PdfTemplater\Layout\Document;
use PdfTemplater\Node\Node;

class BasicBuilder implements Builder
{

    /**
     * Accepts a Node tree as input, performs the layout process and returns the final Document.
     *
     * @param Node $rootNode The tree of Nodes obtained from the Parser.
     * @return Document The final, laid-out Document.
     */
    public function build(Node $rootNode): Document
    {
        return $this->buildDocument($rootNode);
    }

    /**
     * Builds a Document from a Node tree.
     *
     * @param Node $rootNode
     * @return BasicDocument
     */
    protected function buildDocument(Node $rootNode): BasicDocument
    {
        if ($rootNode->getType() !== 'document') {
            throw new BuildException('Root node must be a document!');
        }

        $document = new BasicDocument();

        foreach (['author', 'title', 'keywords'] as $item) {
            $document->setMetadataValue($item, $rootNode->getAttribute($item));
        }
        unset($item);

        $document->setMetadataValue('id', $rootNode->getId());

        foreach ($rootNode->getChildren() as $childNode) {
            if ($childNode->getType() === 'page') {
                $document->addPage($this->buildPage($childNode));
            }
        }
        unset($childNode);

        return $document;
    }

    /**
     * Verifies that the provided attribute is a valid page number.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkPageNumber(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute) &&
            ((float)(int)$attribute === (float)$attribute) &&
            (int)$attribute >= 0;
    }

    /**
     * Verifies that the provided attribute is a valid dimension.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkDimension(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute) &&
            (float)$attribute >= 0.00;
    }

    /**
     * Verifies that the provided attribute is a valid offset.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkOffset(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute);
    }

    /**
     * Builds a page from a Node tree.
     *
     * @param Node $pageNode
     * @return BasicPage
     */
    protected function buildPage(Node $pageNode): BasicPage
    {
        if (!$this->checkPageNumber($pageNode->getAttribute('number'))) {
            throw new BuildException('Page number must be an integer 0 or greater.');
        }

        if (!$this->checkDimension($pageNode->getAttribute('width'))) {
            throw new BuildException('Page width must be a float 0 or greater.');
        }

        if (!$this->checkDimension($pageNode->getAttribute('height'))) {
            throw new BuildException('Page height must be a float 0 or greater.');
        }

        $page = new BasicPage((int)$pageNode->getAttribute('number'));

        $page->setWidth((float)$pageNode->getAttribute('width'));
        $page->setHeight((float)$pageNode->getAttribute('height'));

        $this->placeElements(\array_filter($pageNode->getChildren(), function (Node $elementNode) {
            return !\in_array($elementNode->getType(), ['document', 'page'], true);
        }), $page);

        return $page;
    }

    /**
     * @param Node[]    $elementNodes
     * @param BasicPage $page
     */
    private function placeElements(array $elementNodes, BasicPage $page)
    {

    }
}