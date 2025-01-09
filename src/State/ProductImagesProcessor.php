<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ProductImagesResponseDTO;
use App\Entity\ImagesProducts;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Image;

class ProductImagesProcessor implements ProcessorInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {

    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ProductImagesResponseDTO
    {
        $product = new Products();
        $product ->setName($data->name);

        foreach ($data->imageFile as $imageFile) {
            $image = new ImagesProducts();
            $image ->setImageFile($imageFile);

            $product->addImagesProduct($image);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $imagesNames = [];
        $arrayImages = $product->getImagesProducts();
        foreach ($arrayImages as $image) {
            $imagesNames[] = $image->getName();

        }

        $output = new ProductImagesResponseDTO(
            $product->getName(),
            $imagesNames
        );
        return $output;
    }
}
