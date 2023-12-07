<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('product:detail')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('product:detail')]
    private ?string $country_code = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: MobilePhone::class)]
    private Collection $mobilePhones;

    public function __construct()
    {
        $this->mobilePhones = new ArrayCollection();
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

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(string $country): static
    {
        $this->country_code = $country;

        return $this;
    }

    /**
     * @return Collection<int, MobilePhone>
     */
    public function getMobilePhones(): Collection
    {
        return $this->mobilePhones;
    }

    public function addMobilePhone(MobilePhone $mobilePhone): static
    {
        if (!$this->mobilePhones->contains($mobilePhone)) {
            $this->mobilePhones->add($mobilePhone);
            $mobilePhone->setBrand($this);
        }

        return $this;
    }

    public function removeMobilePhone(MobilePhone $mobilePhone): static
    {
        if ($this->mobilePhones->removeElement($mobilePhone)) {
            // set the owning side to null (unless already changed)
            if ($mobilePhone->getBrand() === $this) {
                $mobilePhone->setBrand(null);
            }
        }

        return $this;
    }
}
