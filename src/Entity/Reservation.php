<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column]
    private ?int $totalDays = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Room $isAbout = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $reservedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(\DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    public function getTotalDays(): ?int
    {
        return $this->totalDays;
    }

    public function setTotalDays(int $totalDays): static
    {
        $this->totalDays = $totalDays;

        return $this;
    }

    public function getIsAbout(): ?Room
    {
        return $this->isAbout;
    }

    public function setIsAbout(?Room $isAbout): static
    {
        $this->isAbout = $isAbout;

        return $this;
    }

    public function getReservedBy(): ?User
    {
        return $this->reservedBy;
    }

    public function setReservedBy(?User $reservedBy): static
    {
        $this->reservedBy = $reservedBy;

        return $this;
    }
}
