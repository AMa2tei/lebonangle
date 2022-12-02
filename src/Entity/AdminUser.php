<?php
	
	namespace App\Entity;
	
	use ApiPlatform\Metadata\ApiResource;
	use ApiPlatform\Metadata\Get;
	use App\Repository\AdminUserRepository;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
	use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Validator\Constraints as Assert;
	
	#[ApiResource(
		operations : [
			new Get(),
		]
	)]
	#[ORM\Entity(repositoryClass : AdminUserRepository::class)]
	#[UniqueEntity(fields : ['email'], message : 'There is already an account with this email')]
	class AdminUser
		implements
		UserInterface,
		PasswordAuthenticatedUserInterface
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\Column(length : 180, unique : true)]
		#[Assert\NotBlank]
		#[Assert\Email]
		private ?string $email = null;
		
		#[ORM\Column]
		private array $roles = [];
		
		#[Assert\Length(min : 5)]
//		#[Assert\NotCompromisedPassword]
		private ?string $plainPassword = null;
		
		/**
		 * @var ?string The hashed password
		 */
		#[ORM\Column]
		private ?string $password = null;
		
		#[ORM\Column(length : 255)]
		private ?string $username = null;
		
		#[ORM\Column(type : 'boolean')]
		private bool $isVerified = false;
		
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
		 * @return string|null
		 */
		public function getPlainPassword(): ?string
		{
			return $this->plainPassword;
		}
		
		/**
		 * @param string|null $plainPassword
		 */
		public function setPlainPassword(?string $plainPassword): void
		{
			$this->plainPassword = $plainPassword;
		}
		
		/**
		 * A visual identifier that represents this user.
		 *
		 * @see UserInterface
		 */
		public function getUserIdentifier(): string
		{
			return (string)$this->email;
		}
		
		/**
		 * The __toString method allows a class to decide how it will react when it is converted to a string.
		 *
		 * @return string
		 * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString(): string
		{
			return sprintf(
				'%s (%s)',
				ucfirst(
					mb_strtolower(
						$this->getUsername()
						??
						''
					)
				),
				ucfirst(mb_strtolower($this->getEmail()))
			);
		}
		
		/**
		 * @see UserInterface
		 */
		public function getRoles(): array
		{
			$roles = $this->roles;
			// guarantee every user at least has ROLE_USER
			$roles[] = 'ROLE_ADMIN';
			
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
		
		public function setPassword(string $password): self
		{
			$this->password = $password;
			
			return $this;
		}
		
		/**
		 * @see UserInterface
		 */
		public function eraseCredentials(): void
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
		
		public function isVerified(): bool
		{
			return $this->isVerified;
		}
		
		public function setIsVerified(bool $isVerified): self
		{
			$this->isVerified = $isVerified;
			
			return $this;
		}
	}
