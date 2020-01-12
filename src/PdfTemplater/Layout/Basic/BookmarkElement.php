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
    private string $name;

    /**
     * @var int
     */
    private int $level;

    /**
     * BookmarkElement constructor.
     *
     * @param string $id
     * @param float  $left
     * @param float  $top
     * @param float  $width
     * @param float  $height
     * @param int    $level
     * @param string $name
     */
    public function __construct(string $id, float $left, float $top, float $width, float $height, int $level, string $name)
    {
        parent::__construct($id, $left, $top, $width, $height);

        $this->setLevel($level);
        $this->setName($name);
    }

    /**
     * Sets the name of the bookmark.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        if ($name === '') {
            throw new LayoutArgumentException('Name cannot be an empty string.');
        }

        $this->name = $name;
    }

    /**
     * Gets the name of the bookmark.
     *
     * @return string
     */
    public function getName(): string
    {
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
        return $this->level;
    }
}