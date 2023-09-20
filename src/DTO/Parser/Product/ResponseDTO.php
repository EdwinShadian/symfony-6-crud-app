<?php

namespace App\DTO\Parser\Product;

class ResponseDTO
{
    public function __construct(
        private string $name,
        private string $description,
        private float $price,
        private string $photoUrl,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(string $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'photoUrl' => $this->photoUrl,
        ];
    }
}
