<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;


use PdfTemplater\Builder\Builder as BuilderInterface;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\ElementFactory;
use PdfTemplater\Layout\Basic\Font;
use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Node\Node;

/**
 * Class Builder
 *
 * A basic implementation of the Builder interface.
 *
 * @package PdfTemplater\Builder\Basic
 */
class Builder implements BuilderInterface
{
    /**
     * Accepts a Node tree as input, performs the layout process and returns the final Document.
     *
     * @param Node $rootNode The tree of Nodes obtained from the Parser.
     * @return Document The final, laid-out Document.
     */
    public function build(Node $rootNode): DocumentInterface
    {
        $builder = new DocumentBuilder();

        return $builder->buildDocument($rootNode);
    }


}