<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(['email', 'company'])]
class Client
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column()]
    #[Groups(['clients:index', 'client:detail'])]
    private ?int $id = null;

    #[Assert\Email()]
    #[ORM\Column(length: 255)]
    #[Groups(['clients:index', 'client:detail'])]
    private ?string $email = null;

    #[Assert\Valid()]
    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $company = null;

    #[ORM\Column(length: 255)]
    #[Groups('client:detail')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups('client:detail')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups('client:detail')]
    private ?string $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?User
    {
        return $this->company;
    }

    public function setCompany(?User $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
