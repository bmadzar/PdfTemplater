<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Layout\Font;
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
    private array $metadata;

    /**
     * @var Page[]
     */
    private array $pages;

    /**
     * @var string|null
     */
    private ?string $filename;

    /**
     * @var Font[]
     */
    private array $fonts;

    /**
     * Document constructor.
     *
     * @param Page[]      $pages
     * @param Font[]      $fonts
     * @param array       $metadata
     * @param string|null $filename
     */
    public function __construct(array $pages, array $fonts, array $metadata, ?string $filename)
    {
        $this->resetPages($pages);
        $this->resetFonts($fonts);
        $this->resetMetadata($metadata);
        $this->setFilename($filename);
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
                throw new \TypeError('Non-scalar metadata value encountered!');
            }
        }
        unset($key, $value);
    }

    /**
     * Clears and sets the full set of metadata.
     *
     * @param string[] $metadata
     */
    public function resetMetadata(array $metadata = []): void
    {
        $this->metadata = [];

        $this->setMetadata($metadata);
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
                throw new \TypeError('Invalid Page supplied!');
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
     * name, it should be replaced.
     *
     * @param Font $font
     */
    public function addFont(Font $font): void
    {
        $this->fonts[$font->getName()] = $font;
    }

    /**
     * Removes the custom font specified by the name from the Document. Nothing should
     * happen if there is no such font.
     *
     * @param string $name
     */
    public function removeFont(string $name): void
    {
        unset($this->fonts[$name]);
    }

    /**
     * Returns TRUE if a font with the given name exists, FALSE otherwise.
     *
     * @param string $name
     * @return bool
     */
    public function hasFont(string $name): bool
    {
        return isset($this->fonts[$name]);
    }

    /**
     * Gets the font filename for the given font name, if it is set. Returns NULL if
     * no font with the given name is set.
     *
     * @param string $name
     * @return Font|null
     */
    public function getFont(string $name): ?Font
    {
        if (isset($this->fonts[$name])) {
            return clone $this->fonts[$name];
        }

        return null;
    }

    /**
     * Returns the entire set of fonts set on the Document, indexed by name.
     *
     * @return Font[]
     */
    public function getFonts(): array
    {
        return \array_map(fn(Font $font) => clone $font, $this->fonts);
    }

    /**
     * Sets the entire collection of fonts.
     *
     * @param Font[] $fonts
     */
    public function setFonts(array $fonts): void
    {
        foreach ($fonts as $font) {
            if ($font instanceof Font) {
                $this->addFont($font);
            } else {
                throw new \TypeError('Invalid Font supplied!');
            }
        }
        unset($font);
    }

    /**
     * Clears and sets the entire collection of fonts.
     *
     * @param Font[] $fonts
     */
    public function resetFonts(array $fonts = []): void
    {
        $this->fonts = [];

        $this->setFonts($fonts);
    }
}