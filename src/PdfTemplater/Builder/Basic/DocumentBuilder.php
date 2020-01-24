<?php
declare(strict_types=1);


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Node\Node;

/**
 * Class DocumentBuilder
 *
 * Helper class to build Documents.
 *
 * @package PdfTemplater\Builder\Basic
 */
class DocumentBuilder
{
    /**
     * Builds a Document from a Node tree.
     *
     * @param Node $rootNode
     * @return Document
     */
    public function buildDocument(Node $rootNode): DocumentInterface
    {
        if ($rootNode->getType() !== 'document') {
            throw new BuildException('Root node must be a document!');
        }

        $document = new Document();

        foreach (['author', 'title', 'keywords'] as $item) {
            $val = $rootNode->getAttribute($item);

            if ($val !== null) {
                $document->setMetadataValue($item, $val);
            }
        }
        unset($item, $val);

        $document->setMetadataValue('id', $rootNode->getId());

        $pageBuilder = new PageBuilder();
        $fontBuilder = new FontBuilder();

        foreach ($rootNode->getChildren() as $childNode) {
            if ($childNode->getType() === 'page') {
                $document->addPage($pageBuilder->buildPage($childNode));
            } elseif ($childNode->getType() === 'font') {
                $document->addFont($fontBuilder->buildFont($childNode));
            }
        }
        unset($childNode);

        return $document;
    }
}