<?php

namespace PdfTemplater\Node;

/**
 * Interface Node
 *
 * This interface must be implemented by all Node classes.
 * A Node is the basic building block of the common internal representation of the textual input
 * files. A Parser produces a Node tree from whatever input format it understands.
 *
 * A Node can have at most one parent, and can have multiple children. Cycles should not happen.
 * A Node must have a unique ID, either set from the outside or generated randomly.
 *
 * @package PdfTemplater\Node
 */
interface Node
{
    /**
     * Sets or replaces the set of child Nodes.
     *
     * @param Node[] $nodes The set of child Nodes.
     */
    public function setChildren(array $nodes): void;

    /**
     * Gets the set of child Nodes. The set may be empty.
     *
     * @return Node[] The set of child Nodes.
     */
    public function getChildren(): array;

    /**
     * Checks if this Node has any children.
     *
     * @return bool True if the Node is not a leaf Node, false otherwise.
     */
    public function hasChildren(): bool;

    /**
     * Adds a Node as a child of this Node. The Node should ensure that there is no
     * duplication of children.
     *
     * @param Node $childNode The Node to add.
     */
    public function addChild(Node $childNode): void;

    /**
     * Removes a Node from the set of children. Nothing should happen if the child Node
     * is not actually in the set of this Node's children.
     *
     * @param Node $childNode The Node to remove.
     */
    public function removeChild(Node $childNode): void;

    /**
     * Checks if the Node is in the set of this Node's children. Only the immediate
     * children should be checked.
     *
     * @param Node $childNode The Node to find.
     * @return bool True if $childNode is one of the immediate children of this Node.
     */
    public function hasChild(Node $childNode): bool;

    /**
     * Sets a reference to the parent Node. Can be NULL if this is the root of the tree.
     *
     * @param null|Node $parent The parent Node.
     */
    public function setParent(?Node $parent): void;

    /**
     * Gets the parent Node. Can be NULL if this is the root of the tree.
     *
     * @return null|Node The parent Node.
     */
    public function getParent(): ?Node;

    /**
     * Checks if this Node has a parent Node.
     *
     * @return bool True if the Node is not the root Node, false otherwise.
     */
    public function hasParent(): bool;

    /**
     * Sets the unique identifier for this Node.
     *
     * @param string $id The new ID.
     */
    public function setId(string $id): void;

    /**
     * Gets the unique identifier for this Node. Must always return a string value.
     *
     * @return string The ID.
     */
    public function getId(): string;

    /**
     * Finds a child Node by its unique identifier.
     *
     * @param string $id The identifier to search for.
     * @return null|Node The Node, or NULL if nothing is found.
     */
    public function findById(string $id): ?Node;
}