<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 */
class Pokemon
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $author;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $type;

	/**
	 * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="pokemon")
	 */
	private $category;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $size;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $weight;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $sex;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $catch_rate;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $color;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $attitude;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $differences;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $evolution;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $talent;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $image_url;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pokemons")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $num_pokedex;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getAuthor(): ?string
	{
		return $this->author;
	}

	public function setAuthor(string $author): self
	{
		$this->author = $author;

		return $this;
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

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(string $type): self
	{
		$this->type = $type;

		return $this;
	}

	public function getCategory(): ?Category
	{
		return $this->category;
	}

	public function setCategory(?Category $category): self
	{
		$this->category = $category;

		return $this;
	}

	public function getSize(): ?int
	{
		return $this->size;
	}

	public function setSize(int $size): self
	{
		$this->size = $size;

		return $this;
	}

	public function getWeight(): ?int
	{
		return $this->weight;
	}

	public function setWeight(int $weight): self
	{
		$this->weight = $weight;

		return $this;
	}

	public function getSex(): ?string
	{
		return $this->sex;
	}

	public function setSex(string $sex): self
	{
		$this->sex = $sex;

		return $this;
	}

	public function getCatchRate(): ?string
	{
		return $this->catch_rate;
	}

	public function setCatchRate(string $catch_rate): self
	{
		$this->catch_rate = $catch_rate;

		return $this;
	}

	public function getColor(): ?string
	{
		return $this->color;
	}

	public function setColor(string $color): self
	{
		$this->color = $color;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getAttitude(): ?string
	{
		return $this->attitude;
	}

	public function setAttitude(string $attitude): self
	{
		$this->attitude = $attitude;

		return $this;
	}

	public function getDifferences(): ?string
	{
		return $this->differences;
	}

	public function setDifferences(string $differences): self
	{
		$this->differences = $differences;

		return $this;
	}

	public function getEvolution(): ?string
	{
		return $this->evolution;
	}

	public function setEvolution(?string $evolution): self
	{
		$this->evolution = $evolution;

		return $this;
	}

	public function getTalent(): ?string
	{
		return $this->talent;
	}

	public function setTalent(string $talent): self
	{
		$this->talent = $talent;

		return $this;
	}

	public function getImageUrl(): ?string
	{
		return $this->image_url;
	}

	public function setImageUrl(string $image_url): self
	{
		$this->image_url = $image_url;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getNumPokedex(): ?int
	{
		return $this->num_pokedex;
	}

	public function setNumPokedex(int $num_pokedex): self
	{
		$this->num_pokedex = $num_pokedex;
		return $this;
	}
}
