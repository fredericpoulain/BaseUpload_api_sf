<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use App\ApiResource\ProductImagesRequestDTO;
use App\ApiResource\ProductImagesResponseDTO;
use App\Repository\ProductsRepository;
use App\State\ProductImagesProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[Post(
    input: ProductImagesRequestDTO::class,
    output: ProductImagesResponseDTO::class,
    processor: ProductImagesProcessor::class
)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ImagesProducts>
     */
    #[ORM\OneToMany(targetEntity: ImagesProducts::class, mappedBy: 'product', cascade: ['persist'] ,orphanRemoval: true)]
    private Collection $imagesProducts;

    public function __construct()
    {
        $this->imagesProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ImagesProducts>
     */
    public function getImagesProducts(): Collection
    {
        return $this->imagesProducts;
    }

    public function addImagesProduct(ImagesProducts $imagesProduct): static
    {
        if (!$this->imagesProducts->contains($imagesProduct)) {
            $this->imagesProducts->add($imagesProduct);
            $imagesProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImagesProduct(ImagesProducts $imagesProduct): static
    {
        if ($this->imagesProducts->removeElement($imagesProduct)) {
            // set the owning side to null (unless already changed)
            if ($imagesProduct->getProduct() === $this) {
                $imagesProduct->setProduct(null);
            }
        }

        return $this;
    }
}
