<?php

namespace PdfTemplater\Parser;


use PdfTemplater\Node\Node;

interface Parser
{
    public function parse(string $data): Node;
}