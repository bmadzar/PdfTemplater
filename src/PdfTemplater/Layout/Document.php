<?php

namespace PdfTemplater\Layout;


interface Document
{
    public function setTitle(string $title);

    public function getTitle(): string;

    public function setAuthor(string $author);

    public function getAuthor(): string;

    public function setDescription(string $description);

    public function getDescription(): string;

    public function setCopyright(string $copyright);

    public function getCopyright(): string;

    public function setPages(array $pages);

    public function getPages() : array;

    public function setPage(int $number, Page $page);

    public function getPage(int $number): Page;

    public function setFilename(string $filename);

    public function getFilename(): string;

    public function setUnits(string $units);

    public function getUnits(): string;
}