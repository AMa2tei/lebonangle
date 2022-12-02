<?php
	
	namespace App\Controller;
	
	use App\Repository\AdminUserRepository;
	use App\Repository\AdvertRepository;
	use App\Repository\CategoryRepository;
	use App\Service\AdvertWorkflow;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Workflow\Registry;
	
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
				                     'user'            => $user,
			                     ]);
		}
		
		#[Route('/admin/validation/publish/{id}', name : 'app_validation_publish')]
		public function publishAdvert(AdvertRepository $advertRepository,
		                              Registry         $registry,
		                              Request          $request): Response
		{
			AdvertWorkflow::Publish($request->attributes->get('id'),
			                        $advertRepository,
			                        $registry);
			return $this->redirectToRoute('app_advert_index');
		}
		
		#[Route('/admin/validation/reject/{id}', name : 'app_validation_reject')]
		public function rejectAdvert(AdvertRepository $advertRepository,
		                             Registry         $registry,
		                             Request          $request): Response
		{
			AdvertWorkflow::Rejected($request->attributes->get('id'),
			                         $advertRepository,
			                         $registry);
			return $this->redirectToRoute('app_advert_index');
		}
	}
