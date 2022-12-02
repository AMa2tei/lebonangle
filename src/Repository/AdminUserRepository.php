<?php
	
	namespace App\Repository;
	
	use App\Entity\AdminUser;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\ORM\NonUniqueResultException;
	use Doctrine\Persistence\ManagerRegistry;
	use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
	use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
	use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
	use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
	
	use function get_class;
	
	/**
	 * @extends ServiceEntityRepository<AdminUser>
	 *
	 * @method AdminUser|null find($id, $lockMode = null, $lockVersion = null)
	 * @method AdminUser|null findOneBy(array $criteria, array $orderBy = null)
	 * @method AdminUser[]    findAll()
	 * @method AdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	 */
	class AdminUserRepository
		extends
		ServiceEntityRepository
		implements
		UserLoaderInterface
	{
		public function __construct(ManagerRegistry $registry)
		{
			parent::__construct($registry,
			                    AdminUser::class);
		}
		
		public function save(AdminUser $entity,
		                     bool      $flush = false): void
		{
			$this->getEntityManager()
			     ->persist($entity);
			
			if ($flush) {
				$this->getEntityManager()
				     ->flush();
			}
		}
		
		public function remove(AdminUser $entity,
		                       bool      $flush = false): void
		{
			$this->getEntityManager()
			     ->remove($entity);
			
			if ($flush) {
				$this->getEntityManager()
				     ->flush();
			}
		}
		
		/**
		 * Used to upgrade (rehash) the user's password automatically over time.
		 */
		public function upgradePassword(PasswordAuthenticatedUserInterface $user,
		                                string                             $newHashedPassword): void
		{
			if ( !$user
			      instanceof
			      AdminUser) {
				throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.',
				                                           get_class($user)));
			}
			
			$user->setPassword($newHashedPassword);
			
			$this->save($user,
			            true);
		}

//    /**
//     * @return AdminUser[] Returns an array of AdminUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdminUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
		/**
		 * @throws \Doctrine\ORM\NonUniqueResultException
		 */
		public function loadUserByIdentifier(string $identifier): ?AdminUser
		{
			$entityManager = $this->getEntityManager();
			
			return $entityManager->createQuery(
				'SELECT u
                FROM App\Entity\AdminUser u
                WHERE u.username = :query
                OR u.email = :query'
			)
			                     ->setParameter('query',
			                                    $identifier)
			                     ->getOneOrNullResult();
		}
	}
