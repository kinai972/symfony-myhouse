<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoomRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 130)]
    #[Assert\NotBlank(message: "Le titre de la chambre est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le titre de la chambre doit contenir {{ limit }} caractères minimum.",
        max: 130,
        maxMessage: "Le titre de la chambre doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le description courte de la chambre est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le description courte de la chambre doit contenir {{ limit }} caractères minimum.",
        max: 255,
        maxMessage: "Le description courte de la chambre doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le description longue de la chambre est obligatoire.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le description longue de la chambre doit contenir {{ limit }} caractères minimum.",
        max: 700,
        maxMessage: "Le description longue de la chambre doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $longDescription = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Assert\Image(
        maxSize: '1M',
        maxSizeMessage: "Veuillez téléverser une image dont le poids n'excède pas 1 Megaoctet."
    )]
    #[Assert\NotBlank(message: 'L\'image de la chambre est obligatoire.', groups: ['create'])]
    #[Vich\UploadableField(mapping: 'rooms', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le prix de la chambre doit être supérieur à 0.")]
    #[Assert\LessThanOrEqual(
        value: 9999.99,
        message: "Le prix de la chambre ne doit pas excéder {{ compared_value }} €.",
    )]
    private ?float $night = null;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    #[Gedmo\Timestampable]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id . ' - ' . $this->title;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getNight(): ?float
    {
        return $this->night;
    }

    public function setNight(float $night): self
    {
        $this->night = $night;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setRoom($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getRoom() === $this) {
                $booking->setRoom(null);
            }
        }

        return $this;
    }
}
