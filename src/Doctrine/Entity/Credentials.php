<?php

namespace CodedMonkey\Conductor\Doctrine\Entity;

use CodedMonkey\Conductor\Doctrine\Repository\CredentialsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity(repositoryClass: CredentialsRepository::class)]
class Credentials
{
    #[Column]
    #[GeneratedValue]
    #[Id]
    public ?int $id = null;

    #[Column]
    public ?string $name = null;

    #[Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[Column(type: Types::STRING, enumType: CredentialsType::class)]
    public CredentialsType|string $type = CredentialsType::HttpBasic;

    #[Column(nullable: true)]
    public ?string $username = null;

    #[Column(nullable: true)]
    public ?string $password = null;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getType(): CredentialsType|string
    {
        return $this->type;
    }

    public function setType(CredentialsType|string $type): void
    {
        $this->type = $type;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
