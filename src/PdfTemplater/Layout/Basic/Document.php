<?php
declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Layout\LayoutArgumentException;
use PdfTemplater\Layout\Page;
use Ramsey\Uuid\Uuid;

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
     * Document constructor.
     */
    public function __construct()
    {
        $this->metadata = [];
        $this->pages = [];
        $this->filename = Uuid::uuid4()->toString();
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
}