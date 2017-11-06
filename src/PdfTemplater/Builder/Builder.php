<?php

namespace PdfTemplater\Builder;


use PdfTemplater\Layout\Document;
use PdfTemplater\Node\Node;

interface Builder
{
    public function build(Node $rootNode): Document;
}