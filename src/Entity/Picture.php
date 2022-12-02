<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
	use ApiPlatform\Metadata\ApiFilter;
	use ApiPlatform\Metadata\ApiProperty;
	use ApiPlatform\Metadata\ApiResource;
	use ApiPlatform\Metadata\Delete;
	use ApiPlatform\Metadata\Get;
	use ApiPlatform\Metadata\Post;
	use App\Controller\PictureController;
	use App\Repository\PictureRepository;
	use DateTimeImmutable;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\HttpFoundation\File\File;
	use Symfony\Component\Serializer\Annotation\Groups;
	use Vich\UploaderBundle\Mapping\Annotation as Vich;
	use Symfony\Component\Validator\Constraints as Assert;
	
	#[ApiResource(
		types                : ['https://schema.org/MediaObject'],
		operations           : [
			new Get(),
			new Post(
				controller        : PictureController::class,
				openapiContext    : [
					                    'requestBody' => [
						                    'content' => [
							                    'multipart/form-data' => [
								                    'schema' => [
									                    'type'       => 'object',
									                    'properties' => [
										                    'file'   => [
											                    'type'   => 'string',
											                    'format' => 'binary'
										                    ],
										                    'advert' => [
											                    'type'   => 'int',
											                    'format' => 'int'
										                    ]
									                    ]
								                    ]
							                    ]
						                    ]
					                    ]
				                    ],
				validationContext : ['groups' => ['Default', 'media_object_create']],
				deserialize       : false
			),
			new Delete()
		],
		normalizationContext : ['groups' => ['media_object:read']]
	)]
	#[ORM\Entity(repositoryClass : PictureRepository::class)]
	#[Vich\Uploadable]
	class Picture
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ApiProperty(types : ['https://schema.org/contentUrl'])]
		#[Groups(['media_object:read'])]
		public ?string $contentUrl = null;
		
		// NOTE: This is not a mapped field of entity metadata, just a simple property.
		#[Assert\NotNull]
		#[Vich\UploadableField(mapping : 'picture', fileNameProperty : 'path')]
		private ?File $file = null;
		
		#[ORM\Column(length : 255)]
		private ?string $path = null;
		
		#[ORM\Column]
		private ?DateTimeImmutable $createdAt = null;
		
		#[ORM\ManyToOne(inversedBy : 'pictures')]
		#[ORM\JoinColumn(nullable : false)]
		#[ApiFilter(SearchFilter::class, properties : ['advert.id' => 'iexact'])]
		#[Groups('read')]
		private ?Advert $advert = null;
		
		#[ORM\Column]
		private ?DateTimeImmutable $updatedAt = null;
		
		public function getId(): ?int
		{
			return $this->id;
		}
		
		/**
		 * @return \Symfony\Component\HttpFoundation\File\File|null
		 */
		public function getFile(): ?File
		{
			return $this->file;
		}
		
		/**
		 * @param \Symfony\Component\HttpFoundation\File\File|null $file
		 *
		 * @return \App\Entity\Picture
		 */
		public function setFile(?File $file): self
		{
			$this->file = $file;
			
			return $this;
		}
		
		public function getPath(): ?string
		{
			return $this->path;
		}
		
		public function setPath(string $path): self
		{
			$this->path = $path;
			
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
		
		public function getAdvert(): ?Advert
		{
			return $this->advert;
		}
		
		public function setAdvert(?Advert $advert): self
		{
			$this->advert = $advert;
			
			return $this;
		}
		
		public function getUpdatedAt(): ?DateTimeImmutable
		{
			return $this->updatedAt;
		}
		
		public function setUpdatedAt(DateTimeImmutable $updatedAt): self
		{
			$this->updatedAt = $updatedAt;
			
			return $this;
		}
	}
