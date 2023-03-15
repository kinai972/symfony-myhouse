<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    const STARTED_AT = [
        'default' => '2022-01-01 08:00:00',
        'fr' => '1er janvier 2022',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La date de début de la réservation est obligatoire.")]
    #[Assert\GreaterThanOrEqual(
        value: self::STARTED_AT['default'],
        message: "Veuillez choisir une date de départ postérieure au " . self::STARTED_AT['fr'] . ".",
        groups: ['admin']
    )]
    #[Assert\GreaterThanOrEqual(
        value: '+1 day',
        message: "Veuillez choisir une date de départ postérieure d'au moins 24 heures.",
        groups: ['guest']
    )]
    private ?\DateTimeImmutable $startsAt = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La date de fin de la réservation est obligatoire.")]
    #[Assert\GreaterThan(
        propertyPath: 'startsAt',
        message: "La date de fin de la réservation doivent être postérieures à la date de départ."
    )]
    #[Assert\LessThanOrEqual(
        value: '+4 years',
        message: "Les réservations sont ouvertes seulement 4 ans à l'avance.",
    )]
    private ?\DateTimeImmutable $endsAt = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le prénom doit contenir {{ limit }} caractères minimum.",
        max: 100,
        maxMessage: "Le prénom doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le nom doit contenir {{ limit }} caractères minimum.",
        max: 200,
        maxMessage: "Le nom doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    #[Assert\Regex(
        pattern: '/^0\d{9}$/',
        message: 'Le numéro de téléphone ne doit pas contenir de caractères spéciaux et doit être sans espace : 0601020304'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: "Veuillez saisir une adresse électronique valide.")]
    private ?string $email = null;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Room $room = null;

    #[ORM\Column(length: 255)]
    private ?string $roomReference = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeImmutable $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeImmutable $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getRoomReference(): ?string
    {
        return $this->roomReference;
    }

    public function setRoomReference(string $roomReference): self
    {
        $this->roomReference = $roomReference;

        return $this;
    }
}
