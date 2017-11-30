<?php
declare(strict_types=1);

namespace PdfTemplater\Node;

use Ramsey\Uuid\Uuid;

/**
 * Class BasicNode
 *
 * Standard implementation of the Node interface.
 *
 * @package PdfTemplater\Node
 */
class BasicNode implements Node
{
    /**
     * @var Node[]
     */
    private $children;

    /**
     * @var string
     */
    private $id;

    /**
     * @var Node
     */
    private $parent;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string[]
     */
    private $attributes;

    /**
     * BasicNode constructor.
     *
     * @param string   $type
     * @param string[] $attributes
     */
    public function __construct(string $type, array $attributes = [])
    {
        $this->children = [];
        $this->id = Uuid::uuid4()->toString();

        $this->attributes = $attributes;
        $this->type = $type;
    }

    /**
     * Sets or replaces the set of child Nodes.
     *
     * @param Node[] $nodes The set of child Nodes.
     */
    public function setChildren(array $nodes): void
    {
        $this->children = $nodes;
    }

    /**
     * Gets the set of child Nodes. The set may be empty.
     *
     * @return Node[] The set of child Nodes.
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Checks if this Node has any children.
     *
     * @return bool True if the Node is not a leaf Node, false otherwise.
     */
    public function hasChildren(): bool
    {
        return (bool)$this->children;
    }

    /**
     * Adds a Node as a child of this Node. The Node should ensure that there is no
     * duplication of children.
     *
     * @param Node $childNode The Node to add.
     */
    public function addChild(Node $childNode): void
    {
        $this->children[$childNode->getId()] = $childNode;
    }

    /**
     * Removes a Node from the set of children. Nothing should happen if the child Node
     * is not actually in the set of this Node's children.
     *
     * @param Node $childNode The Node to remove.
     */
    public function removeChild(Node $childNode): void
    {
        unset($this->children[$childNode->getId()]);
    }

    /**
     * Checks if the Node is in the set of this Node's children. Only the immediate
     * children should be checked.
     *
     * @param Node $childNode The Node to find.
     * @return bool True if $childNode is one of the immediate children of this Node.
     */
    public function hasChild(Node $childNode): bool
    {
        return isset($this->children[$childNode->getId()]);
    }

    /**
     * Sets a reference to the parent Node. Can be NULL if this is the root of the tree.
     *
     * @param null|Node $parent The parent Node.
     */
    public function setParent(?Node $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * Gets the parent Node. Can be NULL if this is the root of the tree.
     *
     * @return null|Node The parent Node.
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    /**
     * Checks if this Node has a parent Node.
     *
     * @return bool True if the Node is not the root Node, false otherwise.
     */
    public function hasParent(): bool
    {
        return (bool)$this->parent;
    }

    /**
     * Sets the unique identifier for this Node.
     *
     * @param string $id The new ID.
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the unique identifier for this Node. Must always return a string value.
     *
     * @return string The ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Finds a child Node by its unique identifier.
     *
     * @param string $id The identifier to search for.
     * @return null|Node The Node, or NULL if nothing is found.
     */
    public function findById(string $id): ?Node
    {
        if (isset($this->children[$id])) {
            return $this->children[$id];
        }

        foreach ($this->children as $child) {
            if ($found = $child->findById($id)) {
                return $found;
            }
        }
        unset($child, $found);

        return null;
    }

    /**
     * Gets the type of node.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of node.
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Gets the entire attribute set. The attribute set is an associative array of strings.
     *
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Sets the entire attribute set.
     *
     * @param string[] $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Gets the value of the attribute identified by $key.
     *
     * @param string $key
     * @return null|string
     */
    public function getAttribute(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Sets the value of the attribute identified by $key.
     *
     * @param string $key
     * @param string $value
     */
    public function setAttribute(string $key, string $value)
    {
        $this->attributes[$key] = $value;
    }
}