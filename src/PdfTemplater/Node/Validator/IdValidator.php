<?php
declare(strict_types=1);

namespace PdfTemplater\Node\Validator;

use PdfTemplater\Node\Node;

/**
 * Class IdValidator
 *
 * A helper class that will validate a Node tree, checking for duplicated IDs.
 *
 * @package PdfTemplater\Node
 */
class IdValidator implements Validator
{
    /**
     * Checks if all IDs in the subtree are unique.
     *
     * @param Node $rootNode The root of the tree.
     * @return bool True if all IDs are unique, false otherwise.
     */
    public function validate(Node $rootNode): bool
    {
        $ids = $this->getSubtreeIds($rootNode);

        return \count($ids) === \count(\array_unique($ids));
    }

    /**
     * Gets a flat array of all the IDs in the given (sub)tree.
     *
     * @param Node $node The root of the subtree.
     * @return string[] The set of IDs.
     */
    private function getSubtreeIds(Node $node): array
    {
        $ids = [$node->getId()];

        foreach ($node->getChildren() as $childNode) {
            $ids = \array_merge($ids, $this->getSubtreeIds($childNode));
        }
        unset($childNode);

        return $ids;
    }
}