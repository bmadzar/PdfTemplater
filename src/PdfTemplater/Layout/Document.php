<?php

namespace PdfTemplater\Layout;


interface Document
{
    public function setTitle(?string $title): void;

    public function getTitle(): ?string;

    public function setAuthor(?string $author): void;

    public function getAuthor(): ?string;

    public function setDescription(?string $description): void;

    public function getDescription(): ?string;

    public function setCopyright(?string $copyright): void;

    public function getCopyright(): ?string;

    public function setPages(array $pages): void;

    public function getPages() : array;

    public function setPage(int $number, Page $page): void;

    public function getPage(int $number): ?Page;

    public function removePage(int $number): bool;

    public function setFilename(string $filename): void;

    public function getFilename(): string;
}