<?php
declare(strict_types=1);

namespace PdfTemplater\Builder;


use PdfTemplater\Layout\Document;
use PdfTemplater\Node\Node;

/**
 * Interface Builder
 *
 * This interface should be implemented by all Builders.
 * It is the role of the Builder to take the internal tree representation of the input and
 * perform the heavy lifting to lay it out and produce a pixel-perfect Document.
 *
 * @package PdfTemplater\Builder
 */
interface Builder
{
    /**
     * Accepts a Node tree as input, performs the layout process and returns the final Document.
     *
     * @param Node $rootNode The tree of Nodes obtained from the Parser.
     * @return Document The final, laid-out Document.
     */
    public function build(Node $rootNode): Document;
}