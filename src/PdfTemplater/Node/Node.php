<?php

namespace PdfTemplater\Node;


interface Node
{
    public function setChildren(array $nodes): void;

    public function getChildren(): array;

    public function hasChildren(): bool;

    public function setParent(?Node $parent): void;

    public function getParent(): ?Node;

    public function hasParent(): bool;

    public function setId(string $id): void;

    public function getId(): string;

    public function findById(string $id): ?Node;
}