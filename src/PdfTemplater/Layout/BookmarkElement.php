<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;


interface BookmarkElement extends Element
{
    /**
     * Sets the name of the bookmark.
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Gets the name of the bookmark.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets the level (i.e. depth) of the bookmark, minimum is 0.
     *
     * @param int $level
     */
    public function setLevel(int $level): void;

    /**
     * Gets the level/depth of the bookmark.
     *
     * @return int
     */
    public function getLevel(): int;
}