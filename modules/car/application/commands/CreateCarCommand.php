<?php

declare(strict_types=1);

namespace app\modules\car\application\commands;

final class CreateCarCommand
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly string $price,
        private readonly string $photoUrl,
        private readonly string $contacts,
        private readonly ?CreateCarOptionCommand $option
    ) {
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

    public function getOption(): ?CreateCarOptionCommand
    {
        return $this->option;
    }
}
