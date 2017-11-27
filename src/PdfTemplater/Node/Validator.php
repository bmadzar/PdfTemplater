<?php

namespace PdfTemplater\Node;

/**
 * Interface Validator
 *
 * This interface must be implemented by all Node Validators.
 * The role of the Validator is to validate a Node tree.
 *
 * @package PdfTemplater\Node
 */
interface Validator
{
    /**
     * Validate a Node tree.
     *
     * @param Node $rootNode The root of the Node tree to validate.
     * @return bool True if validation passed, false otherwise.
     */
    public function validate(Node $rootNode): bool;
}