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
     * Sets the full set of metadata. The metadata collection is an associative array.
     *
     * @param string[] $metadata
     */
    public function setMetadata(array $metadata): void;

    /**
     * Gets the full set of metadata. Can be empty.
     *
     * @return string[]
     */
    public function getMetadata(): array;

    /**
     * Sets the metadata value at the given $key to the given $value.
     *
     * @param string $key
     * @param string $value
     */
    public function setMetadataValue(string $key, string $value): void;

    /**
     * Gets the metadata value at the given $key. Can be empty.
     *
     * @param string $key
     * @return null|string
     */
    public function getMetadataValue(string $key): ?string;

    /**
     * Checks if there exists a metadata value with the given key. Note that an existent but
     * falsey value will still return TRUE.
     *
     * @param string $key
     * @return bool
     */
    public function hasMetadataValue(string $key): bool;

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