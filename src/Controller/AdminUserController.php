<?php
	
	namespace App\Controller;
	
	use App\Repository\AdminUserRepository;
	use App\Repository\AdvertRepository;
	use App\Repository\CategoryRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdminUserController
		extends
		AbstractController
	{
		#[Route('/admin', name : 'app_admin_user')]
		public function index(
			AdminUserRepository $adminUserRepository,
			CategoryRepository  $categoryRepository,
			AdvertRepository    $advertRepository
		): Response {
			$user      = $this->getUser();
			$adminUser = $adminUserRepository->findAll();
			
			$category = $categoryRepository->findAll();
			$advert   = $advertRepository->findAll();
			return $this->render('admin_user/index.html.twig',
			                     [
				                     'controller_name' => 'Interface Administrateur',
				                     'allAdmin'        => $adminUser,
				                     'allCategory'     => $category,
				                     'allAdvert'       => $advert,
				                     'user'         => $user,
			                     ]);
		}
	}
