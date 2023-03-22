<?php

/*
 * Fruity (This file is Entity and responsible for manage table fruity)
 * Author : Amar Shah
 * Company : QuanticEdge Software Solutions
 */

namespace App\Entity;

use App\Repository\FruityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruityRepository::class)]
class Fruity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $family = null;

    #[ORM\Column]
    private ?int $fruit_id = null;

    #[ORM\Column(length: 255)]
    private ?string $genus = null;

    #[ORM\Column(length: 255)]
    private ?string $f_order = null;

    #[ORM\Column]
    private array $nutritions = [];

    #[ORM\Column]
    private ?bool $is_fav = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFruitId(): ?int
    {
        return $this->fruit_id;
    }

    public function setFruitId(int $fruit_id): self
    {
        $this->fruit_id = $fruit_id;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getFOrder(): ?string
    {
        return $this->f_order;
    }

    public function setFOrder(string $f_order): self
    {
        $this->f_order = $f_order;

        return $this;
    }

    public function getNutritions(): array
    {
        return $this->nutritions;
    }

    public function setNutritions(array $nutritions): self
    {
        $this->nutritions = $nutritions;

        return $this;
    }
    public function getNutritionsAsString(): string
    {
        $nutritionsArray = $this->nutritions;
        $nutritionsString = '';

        foreach ($nutritionsArray as $key => $value) {
            $nutritionsString .= $key . ': ' . $value . ', ';
        }

        return rtrim($nutritionsString, ', ');
    }
    public function getSum(): string
    {
        $nutritionsArray = $this->nutritions;
        return array_sum($nutritionsArray);
    }

    public function isIsFav(): ?bool
    {
        return $this->is_fav;
    }

    public function setIsFav(bool $is_fav): self
    {
        $this->is_fav = $is_fav;

        return $this;
    }
}
