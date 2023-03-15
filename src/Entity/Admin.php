<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name: '`admin`')]
#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[UniqueEntity(fields: 'email', message: "Cette adresse électronique est déjà utilisée.")]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "Veuillez saisir une adresse électronique valide.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_!:;,.*?+=%$§@&#])(?!.*\s)(.{12,20})$/',
        message: 'Le mot de passe doit être sans espace et contenir au moins une lettre minuscule, une lettre majuscule, un chiffre, un caractère spécial (-_!:;,.*?+=%$§@&#) et doit avoir entre 12 et 20 caractères.'
    )]
    #[Assert\NotBlank(
        message: "Le mot de passe est obligatoire.",
        groups: ['password'],
    )]
    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9_-]{6,15}$/',
        message: 'Le pseudo doit être sans espace et contenir entre 6 et 15 caractères alphanumériques ("abc", "ABC"), y compris les tirets ("-") et les underscores ("_")'
    )]
    #[Assert\NotBlank(
        message: "Le pseudo est obligatoire.",
    )]
    private ?string $username = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le nom doit contenir {{ limit }} caractères minimum.",
        max: 200,
        maxMessage: "Le nom doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le prénom doit contenir {{ limit }} caractères minimum.",
        max: 100,
        maxMessage: "Le prénom doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: "La civilité est obligatoire.")]
    #[Assert\Choice(choices: ['m', 'f'], message: 'Veuillez sélectionner une civilité.')]
    private ?string $gender = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeImmutable $registeredAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_ADMIN
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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
}
