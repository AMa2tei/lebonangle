<?php
	
	namespace App\Controller\Api;
	
	use App\Entity\Picture;
	use DateTimeImmutable;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Attribute\AsController;
	use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
	
	#[AsController]
	final class CreatePictureAction
		extends
		AbstractController
	{
		/**
		 * @var \Doctrine\ORM\EntityManagerInterface
		 */
		private EntityManagerInterface $manager;
		
		/**
		 * @param \Doctrine\ORM\EntityManagerInterface $manager
		 */
		public function __construct(EntityManagerInterface $manager)
		{
			$this->manager = $manager;
		}
		
		public function __invoke(Request $request): Picture
		{
			$uploadedFile = $request->files->get('file');
			
			if ( !$uploadedFile) {
				throw new BadRequestHttpException('"file" is required');
			}
			
			$picture = new Picture();
			$picture->setFile($uploadedFile);
			$picture->setCreatedAt(new DateTimeImmutable());
			
			return $picture;
		}
	}