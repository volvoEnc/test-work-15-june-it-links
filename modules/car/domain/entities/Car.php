<?php

declare(strict_types=1);

namespace app\modules\car\domain\entities;

final class Car
{
    private function __construct(
        private readonly ?int $id,
        private readonly string $title,
        private readonly string $description,
        private readonly string $price,
        private readonly string $photoUrl,
        private readonly string $contacts,
        private readonly ?string $createdAt,
        private readonly ?CarOption $option
    ) {
    }

    public static function create(
        string $title,
        string $description,
        string $price,
        string $photoUrl,
        string $contacts,
        ?CarOption $option
    ): self {
        return new self(null, $title, $description, $price, $photoUrl, $contacts, null, $option);
    }

    public static function fromDatabase(
        int $id,
        string $title,
        string $description,
        string $price,
        string $photoUrl,
        string $contacts,
        string $createdAt,
        ?CarOption $option
    ): self {
        return new self($id, $title, $description, $price, $photoUrl, $contacts, $createdAt, $option);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function getContacts(): string
    {
        return $this->contacts;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getOption(): ?CarOption
    {
        return $this->option;
    }
}
