<?php

namespace App\ApiResource;

class ProductImagesResponseDTO
{

    public function __construct(
        public string $name,
        public array $imageFile,
        public array $imageUrls = []
    ){}
}