<?php

namespace App\Entity;

use App\Repository\MobilePhoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MobilePhoneRepository::class)]
class MobilePhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['products:index', 'product:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['products:index', 'product:detail'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['products:index', 'product:detail'])]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('product:detail')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'mobilePhones')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('product:detail')]
    private ?Brand $brand = null;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
