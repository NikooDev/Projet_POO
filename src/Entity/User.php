<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=150)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=150)
	 */
	private $firstname;

	/**
	 * @ORM\OneToMany(targetEntity=Pokemon::class, mappedBy="user", orphanRemoval=true)
	 */
	private $pokemons;

	public function __construct()
      	{
      		$this->pokemons = new ArrayCollection();
      	}

	public function getId(): ?int
      	{
      		return $this->id;
      	}

	/**
	 * @deprecated since Symfony 5.3, use getUserIdentifier instead
	 */
	public function getUsername(): string
      	{
      		return (string)$this->username;
      	}

	public function setUsername(?string $username): self
      	{
      		$this->username = $username;
      
      		return $this;
      	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
      	{
      		return (string)$this->username;
      	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
      	{
      		$roles = $this->roles;
      		// guarantee every user at least has ROLE_USER
      		$roles[] = 'ROLE_USER';
      
      		return array_unique($roles);
      	}

	public function setRoles(array $roles): self
      	{
      		$this->roles = $roles;
      
      		return $this;
      	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
      	{
      		return $this->password;
      	}

	public function setPassword(?string $password): self
      	{
      		$this->password = $password;
      
      		return $this;
      	}

	/**
	 * Returning a salt is only needed, if you are not using a modern
	 * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
      	{
      		return null;
      	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
      	{
      		// If you store any temporary, sensitive data on the user, clear it here
      		// $this->plainPassword = null;
      	}

	public function getName(): string
      	{
      		return (string)$this->name;
      	}

	public function setName(?string $name): self
      	{
      		$this->name = $name;
      
      		return $this;
      	}

	public function getFirstname(): string
      	{
      		return (string)$this->firstname;
      	}

	public function setFirstname(?string $firstname): self
      	{
      		$this->firstname = $firstname;
      
      		return $this;
      	}

	/**
	 * @return Collection<int, Pokemon>
	 */
	public function getPokemons(): Collection
      	{
      		return $this->pokemons;
      	}

	public function addPokemon(Pokemon $pokemon): self
      	{
      		if (!$this->pokemons->contains($pokemon)) {
      			$this->pokemons[] = $pokemon;
      			$pokemon->setUserId($this);
      		}
      
      		return $this;
      	}

	public function removePokemon(Pokemon $pokemon): self
      	{
      		if ($this->pokemons->removeElement($pokemon)) {
      			// set the owning side to null (unless already changed)
      			if ($pokemon->getUserId() === $this) {
      				$pokemon->setUserId(null);
      			}
      		}
      
      		return $this;
      	}
}
