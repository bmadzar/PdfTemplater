<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\Page;

/**
 * Class Document
 *
 * A basic implementation of the Document interface using PHP arrays.
 *
 * @package PdfTemplater\Layout\Basic
 */
class Document implements DocumentInterface
{
    /**
     * @var string[]
     */
    private $metadata;

    /**
     * @var Page[]
     */
    private $pages;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string[]
     */
    private $fonts;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->metadata = [];
        $this->pages = [];
        $this->fonts = [];
    }

    /**
     * Sets the full set of metadata. The metadata collection is an associative array.
     *
     * @param string[] $metadata
     */
    public function setMetadata(array $metadata): void
    {
        foreach ($metadata as $key => $value) {
            if (\is_scalar($value)) {
                $this->setMetadataValue((string)$key, (string)$value);
            } else {
                throw new LayoutArgumentException('Non-scalar metadata value encountered!');
            }
        }
        unset($key, $value);
    }

    /**
     * Gets the full set of metadata. Can be empty.
     *
     * @return string[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets the metadata value at the given $key to the given $value.
     *
     * @param string $key
     * @param string $value
     */
    public function setMetadataValue(string $key, string $value): void
    {
        $this->metadata[$key] = $value;
    }

    /**
     * Gets the metadata value at the given $key. Can be empty.
     *
     * @param string $key
     * @return null|string
     */
    public function getMetadataValue(string $key): ?string
    {
        return $this->metadata[$key] ?? null;
    }

    /**
     * Checks if there exists a metadata value with the given key. Note that an existent but
     * falsey value will still return TRUE.
     *
     * @param string $key
     * @return bool
     */
    public function hasMetadataValue(string $key): bool
    {
        return isset($this->metadata[$key]);
    }

    /**
     * Sets the full set of Pages. Pages should be indexed numerically.
     *
     * @param Page[] $pages
     */
    public function setPages(array $pages): void
    {
        foreach ($pages as $number => $page) {
            if ($page instanceof Page && \is_numeric($number)) {
                $this->addPage($page);
            } else {
                throw new LayoutArgumentException('Invalid Page supplied!');
            }
        }
        unset($number, $page);
    }

    /**
     * Clears and optionally sets the full set of Pages. Pages should be indexed numerically.
     *
     * @param Page[] $pages
     */
    public function resetPages(array $pages = []): void
    {
        $this->pages = [];

        $this->setPages($pages);
    }

    /**
     * Gets the current set of Pages. Can be empty.
     *
     * @return Page[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Adds a page to the set of Pages.
     *
     * @param Page $page
     */
    public function addPage(Page $page): void
    {
        $this->pages[$page->getNumber()] = $page;
    }

    /**
     * Gets the Page at the given index. Returns NULL if there is no such Page.
     *
     * @param int $number
     * @return null|Page
     */
    public function getPage(int $number): ?Page
    {
        return $this->pages[$number] ?? null;
    }

    /**
     * Removes the Page at the given index. Nothing should happen if there is no such
     * Page in the set.
     *
     * @param int $number
     */
    public function removePage(int $number): void
    {
        unset($this->pages[$number]);
    }

    /**
     * Sets the Document filename.
     *
     * @param null|string $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Gets the Document filename.
     *
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Adds a custom font to the Document. If there is an existing font with the supplied
     * alias, it should be replaced.
     *
     * @param string $file
     * @param string $alias
     */
    public function addFont(string $file, string $alias): void
    {
        if (!$alias || !\trim($file)) {
            throw new LayoutArgumentException('Both font name and alias must be non-empty!');
        }

        $this->fonts[$alias] = $file;
    }

    /**
     * Removes the custom font specified by the alias from the Document. Nothing should
     * happen if there is no such font.
     *
     * @param string $alias
     */
    public function removeFont(string $alias): void
    {
        unset($this->fonts[$alias]);
    }

    /**
     * Returns TRUE if a font with the given alias exists, FALSE otherwise.
     *
     * @param string $alias
     * @return bool
     */
    public function hasFont(string $alias): bool
    {
        return isset($this->fonts[$alias]);
    }

    /**
     * Gets the font filename for the given font alias, if it is set. Returns NULL if
     * no font with the given alias is set.
     *
     * @param string $alias
     * @return string|null
     */
    public function getFont(string $alias): ?string
    {
        if (isset($this->fonts[$alias])) {
            return $this->fonts[$alias];
        }

        return null;
    }

    /**
     * Returns the entire set of fonts set on the Document, indexed by alias.
     *
     * @return string[]
     */
    public function getFonts(): array
    {
        return $this->fonts;
    }
}