<?php

declare(strict_types=1);

namespace PdfTemplater\Test;


class DataFile
{
    private string $fileName;

    /**
     * @var resource
     */
    private $handle;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->open();
    }

    public function __destruct()
    {
        $this->close();
    }

    protected function open()
    {
        $gzFile = $this->fileName . '.gz';

        if (!\file_exists($this->fileName) && \is_file($gzFile) && \is_readable($gzFile)) {
            $this->handle = \fopen($gzFile, 'r');

            if (!$this->handle) {
                throw new \RuntimeException(\sprintf('Could not open file for reading: %s', $gzFile));
            }

            \stream_filter_append($this->handle, 'zlib.deflate', \STREAM_FILTER_READ);
        } else {
            $this->handle = \fopen($this->fileName, 'r');

            if (!$this->handle) {
                throw new \RuntimeException(\sprintf('Could not open file for reading: %s', $this->fileName));
            }
        }
    }

    protected function close()
    {
        if ($this->handle) {
            \fclose($this->handle);
        }
    }

    /**
     * Gets a line from a CSV file, parsed out into an array.
     * Returns NULL if there are no more lines.
     *
     * @return string[]|null
     */
    public function getParsedLine(): ?array
    {
        $line = \fgetcsv($this->handle);

        return \is_array($line) ? $line : null;
    }
}