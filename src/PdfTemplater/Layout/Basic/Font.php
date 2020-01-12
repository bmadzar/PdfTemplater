<?php


namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\Font as FontInterface;
use PdfTemplater\Layout\LayoutArgumentException;

class Font implements FontInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $style;

    /**
     * @var string|null
     */
    private ?string $file;

    /**
     * Font constructor.
     *
     * @param string $name
     * @param int $style
     * @param string|null $file
     */
    public function __construct(string $name, int $style, ?string $file)
    {
        $this->setName($name);
        $this->setStyle($style);
        $this->setFile($file);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        if ($name === '') {
            throw new LayoutArgumentException('Font name cannot be empty.');
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $style
     */
    public function setStyle(int $style): void
    {
        if (!\in_array($style, self::STYLES, true)) {
            throw new LayoutArgumentException('Invalid style supplied.');
        }

        $this->style = $style;
    }

    /**
     * @inheritDoc
     */
    public function getStyle(): int
    {
        return $this->style;
    }

    /**
     * @inheritDoc
     */
    public function setFile(?string $file): void
    {
        if ($file === '') {
            throw new LayoutArgumentException('Font file must be a non-empty string or null.');
        }

        if ($file !== null) {
            if (!\is_readable($file) || !\is_file($file)) {
                throw new LayoutArgumentException('Font file not readable.');
            }

            $file = \realpath($file);

            if ($file === false) {
                throw new LayoutArgumentException('Could not normalize font file path.');
            }
        }

        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
}