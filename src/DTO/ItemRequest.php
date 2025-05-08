<?php

namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class ItemRequest
{
    public ?string $uuid = null;
    public ?array $info = null;
    public ?string $product_uuid = null;
    public ?string $location_uuid = null;
    public ?int $status = null;
    public ?bool $accessible = null;
}

