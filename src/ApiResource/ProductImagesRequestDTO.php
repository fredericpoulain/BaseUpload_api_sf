<?php

namespace App\ApiResource;

class ProductImagesRequestDTO
{

    public function __construct(
        public string $name,
        public array $imageFile,

    ){}
}