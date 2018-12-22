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
     * Adds an individual Page to the current set of Pages.
     *
     * @param Page $page
     */
    public function addPage(Page $page): void;

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

    /**
     * Adds a custom font to the Document. If there is an existing font with the supplied
     * alias, it should be replaced.
     *
     * @param string $file
     * @param string $alias
     */
    public function addFont(string $file, string $alias): void;

    /**
     * Removes the custom font specified by the alias from the Document. Nothing should
     * happen if there is no such font.
     *
     * @param string $alias
     */
    public function removeFont(string $alias): void;

    /**
     * Returns TRUE if a font with the given alias exists, FALSE otherwise.
     *
     * @param string $alias
     * @return bool
     */
    public function hasFont(string $alias): bool;

    /**
     * Gets the font filename for the given font alias, if it is set. Returns NULL if
     * no font with the given alias is set.
     *
     * @param string $alias
     * @return string|null
     */
    public function getFont(string $alias): ?string;

    /**
     * Returns the entire set of fonts set on the Document, indexed by alias.
     *
     * @return string[]
     */
    public function getFonts(): array;
}