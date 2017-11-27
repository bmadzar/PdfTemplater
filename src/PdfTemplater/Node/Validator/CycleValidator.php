<?php

namespace PdfTemplater\Node\Validator;

use PdfTemplater\Node\Node;

/**
 * Class CycleValidator
 *
 * A helper class that will check a given Node tree for cycles -- a Node cannot
 * appear more than once in a given tree.
 *
 * @package PdfTemplater\Node
 */
class CycleValidator implements Validator
{
    /**
     * @var string[]
     */
    private $objectHashes;

    /**
     * Checks that the given Node tree is free from cycles.
     *
     * @param Node $rootNode The root of the Node tree to validate.
     * @return bool True if validation passed, false otherwise.
     */
    public function validate(Node $rootNode): bool
    {
        $this->objectHashes = [];

        return $this->validateSubtree($rootNode);
    }

    /**
     * Checks the subtree for cycles.
     *
     * @param Node $node The root of the subtree.
     * @return bool True if there are no cycles, false otherwise.
     */
    private function validateSubtree(Node $node): bool
    {
        $hash = \spl_object_hash($node);

        if (\in_array($hash, $this->objectHashes, true)) {
            return false;
        }

        $this->objectHashes[] = $hash;

        foreach ($node->getChildren() as $childNode) {
            if (!$this->validateSubtree($childNode)) {
                return false;
            }
        }
        unset($childNode);

        return true;
    }
}