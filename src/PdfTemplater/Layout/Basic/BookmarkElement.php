<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\BookmarkElement as BookmarkElementInterface;
use PdfTemplater\Layout\LayoutArgumentException;

/**
 * Class BookmarkElement
 *
 * Basic implementation of a Bookmark element.
 *
 * @package PdfTemplater\Layout\Basic
 */
class BookmarkElement extends Element implements BookmarkElementInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $level;

    /**
     * BookmarkElement constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct($id);

        $this->level = 0;
    }

    /**
     * Sets the name of the bookmark.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the name of the bookmark.
     *
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->name;
    }

    /**
     * Sets the level (i.e. depth) of the bookmark, minimum is 0.
     *
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        if ($level < 0) {
            throw new LayoutArgumentException('Bookmark level cannot be less than 0.');
        }

        $this->level = $level;
    }

    /**
     * Gets the level/depth of the bookmark.
     *
     * @return int
     */
    public function getLevel(): int
    {
        if ($this->level === null) {
            throw new LayoutArgumentException('Element not configured completely!');
        }

        return $this->level;
    }

    /**
     * Elements can be partially constructed. This method should return true if and only if
     * all mandatory values have been set.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return parent::isValid() && $this->name;
    }
}