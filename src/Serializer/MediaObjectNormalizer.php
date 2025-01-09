<?php
// api/src/Serializer/MediaObjectNormalizer.php

namespace App\Serializer;

use App\ApiResource\ProductImagesResponseDTO;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MediaObjectNormalizer implements NormalizerInterface
{

    private const ALREADY_CALLED = 'IMAGES_PRODUCTS_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly NormalizerInterface $normalizer,
        private readonly string $uriPrefix

    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        foreach ($object->imageFile as $key => $image) {
            $object->imageUrls[$key] = $this->uriPrefix . $image;
        }
        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {

        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof ProductImagesResponseDTO;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ProductImagesResponseDTO::class => true,
        ];
    }
}