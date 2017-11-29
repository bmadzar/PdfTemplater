<?php
declare(strict_types=1);

namespace PdfTemplater\Layout;

/**
 * Interface Document
 *
 * The Document represents an entire renderable file. It contains a set of Pages.
 *
 * @package PdfTemplater\Layout
 */
interface Document
{
    /**
     * Set the Document title.
     *
     * @param null|string $title
     */
    public function setTitle(?string $title): void;

    /**
     * Gets the Document title.
     *
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * Sets the Document author.
     *
     * @param null|string $author
     */
    public function setAuthor(?string $author): void;

    /**
     * Gets the Document author
     *
     * @return null|string
     */
    public function getAuthor(): ?string;

    /**
     * Sets the Document description.
     *
     * @param null|string $description
     */
    public function setDescription(?string $description): void;

    /**
     * Gets the Document description.
     *
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * Sets the Document copyright.
     *
     * @param null|string $copyright
     */
    public function setCopyright(?string $copyright): void;

    /**
     * Gets the Document copyright.
     *
     * @return null|string
     */
    public function getCopyright(): ?string;

    /**
     * Sets the full set of Pages. Pages should be indexed numerically.
     *
     * @param Page[] $pages
     */
    public function setPages(array $pages): void;

    /**
     * Gets the current set of Pages. Can be empty.
     *
     * @return Page[]
     */
    public function getPages(): array;

    /**
     * Sets an individual Page at the provided index.
     *
     * @param int  $number
     * @param Page $page
     */
    public function setPage(int $number, Page $page): void;

    /**
     * Gets the Page at the given index. Returns NULL if there is no such Page.
     *
     * @param int $number
     * @return null|Page
     */
    public function getPage(int $number): ?Page;

    /**
     * Removes the Page at the given index. Nothing should happen if there is no such
     * Page in the set.
     *
     * @param int $number
     */
    public function removePage(int $number): void;

    /**
     * Sets the Document filename.
     *
     * @param null|string $filename
     */
    public function setFilename(?string $filename): void;

    /**
     * Gets the Document filename.
     *
     * @return null|string
     */
    public function getFilename(): ?string;
}