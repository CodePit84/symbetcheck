<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovementRepository;

#[ORM\Entity(repositoryClass: MovementRepository::class)]
class Movement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $movement = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    // #[ORM\ManyToOne(inversedBy: 'movements')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?user $user_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'movements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovement(): ?float
    {
        return $this->movement;
    }

    public function setMovement(float $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }



    public function getUser_Id(): ?User
    {
        // $id = $this->user_id;
        
        return $this->user_id;

        // return $this->getUser_Id()->getId();
        // dd($this->user_id);
        
    }

    // public function setUser_Id(?User $user_id): self
    // {
    //     $this->user_id = $user_id;

    //     return $this;
    // }

    public function setUser_Id(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }



    // public function __toString()
    // {
    //     return $this->user_id;
    // }


}
