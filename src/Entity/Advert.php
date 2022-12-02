<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
	use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
	use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
	use ApiPlatform\Metadata\ApiFilter;
	use ApiPlatform\Metadata\ApiResource;
	use App\Repository\AdvertRepository;
	use DateTimeImmutable;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Workflow\WorkflowInterface;
	
	#[ORM\Entity(repositoryClass : AdvertRepository::class)]
	#[ApiResource,
		ApiFilter(SearchFilter::class, properties: ['category' => 'exact']),
		ApiFilter(OrderFilter::class, properties: ['price']),
		ApiFilter(RangeFilter::class, properties: ['price']),
	]
	class Advert
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\Column(length : 100)]
		#[Assert\NotBlank] // Répétitif par rapport aux lignes suivantes
		#[Assert\Length(
			min        : 3,
			max        : 100, // Répétitif par rapport aux validations de doctrine.
			minMessage : 'Attention : le titre doit faire plus de 3 caractères.',
			maxMessage : 'Attention : le titre doit faire moins de 100 caractères.',
		)]
		private ?string $title = null;
		
		#[ORM\Column(type : Types::TEXT)]
		#[Assert\Length(
			max        : 1200,
			maxMessage : 'Attention : le contenu ne peut pas faire plus de 1200 caractères.'
		)]
		private ?string $content = null;
		
		#[ORM\Column(length : 255)]
		private ?string $author = null;
		
		#[ORM\Column(length : 255)]
		private ?string $email = null;
		
		#[ORM\ManyToOne(inversedBy : 'adverts')]
		#[ORM\JoinColumn(nullable : false)]
		private ?Category $category = null;
		
		#[ORM\Column]
		#[Assert\Positive] // Répétitif par rapports aux lignes suivantes.
		#[Assert\Range(
			notInRangeMessage : 'Attention : le prix ne peut pas être inférieur à 1.00€ et ne peut pas excéder 1 000 000€',
			min               : 1.00, max : 1_000_000.00
		)]
		private ?float $price = null;
		
		#[ORM\Column(length : 255)]
		private ?string $state = null;
		
		#[ORM\Column]
		private ?DateTimeImmutable $createdAt = null;
		
		#[ORM\Column(nullable : true)]
		private ?DateTimeImmutable $publishedAt = null;
		
		#[ORM\OneToMany(mappedBy : 'advert', targetEntity : Picture::class, orphanRemoval : true)]
		private Collection $pictures;
		
		public function __construct()
		{
			$this->pictures = new ArrayCollection();
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
		
		public function getContent(): ?string
		{
			return $this->content;
		}
		
		public function setContent(string $content): self
		{
			$this->content = $content;
			
			return $this;
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
		
		public function getEmail(): ?string
		{
			return $this->email;
		}
		
		public function setEmail(string $email): self
		{
			$this->email = $email;
			
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
		
		public function getPrice(): ?float
		{
			return $this->price;
		}
		
		public function setPrice(float $price): self
		{
			$this->price = $price;
			
			return $this;
		}
		
		public function getState(): ?string
		{
			return $this->state;
		}
		
		public function setState(string $state): self
		{
			$this->state = $state;
			
			return $this;
		}
		
		public function getCreatedAt(): ?DateTimeImmutable
		{
			return $this->createdAt;
		}
		
		public function setCreatedAt(DateTimeImmutable $createdAt): self
		{
			$this->createdAt = $createdAt;
			
			return $this;
		}
		
		public function getPublishedAt(): ?DateTimeImmutable
		{
			return $this->publishedAt;
		}
		
		public function setPublishedAt(?DateTimeImmutable $publishedAt): self
		{
			$this->publishedAt = $publishedAt;
			
			return $this;
		}
		
		/**
		 * @return Collection<int, Picture>
		 */
		public function getPictures(): Collection
		{
			return $this->pictures;
		}
		
		public function addPicture(Picture $picture): self
		{
			if ( !$this->pictures->contains($picture)) {
				$this->pictures->add($picture);
				$picture->setAdvert($this);
			}
			
			return $this;
		}
		
		public function removePicture(Picture $picture): self
		{
			if ($this->pictures->removeElement($picture)) {
				// set the owning side to null (unless already changed)
				if ($picture->getAdvert() ===
				    $this) {
					$picture->setAdvert(null);
				}
			}
			
			return $this;
		}
		
		/**
		 * The __toString method allows a class to decide how it will react when it is converted to a string.
		 *
		 * @return string
		 * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString(): string
		{
			return $this->title;
		}
		
	}
