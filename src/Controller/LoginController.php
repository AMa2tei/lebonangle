<?php
	
	namespace App\Controller;
	
	use App\Entity\AdminUser;
	use App\Form\AdminUserType;
	use App\Repository\AdminUserRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Exception;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	
	class LoginController
		extends
		AbstractController
	{
		#[Route('/registration', name : 'app_registration')]
		public function addUser(UserPasswordHasherInterface $passwordHasher,
		                        Request                     $request,
		                        EntityManagerInterface      $entityManager): RedirectResponse | Response
		{
			$adminUser = new AdminUser();
			
			$form = $this->createForm(AdminUserType::class,
			                          $adminUser);
			
			$form->handleRequest($request);
			if ($form->isSubmitted() &&
			    $form->isValid()) {
				$hashedPassword = $passwordHasher->hashPassword(
					$adminUser,
					$adminUser->getPlainPassword()
				);
				$adminUser->setPassword($hashedPassword);
				$entityManager->persist($adminUser);
				$entityManager->flush();
				
				return $this->redirectToRoute('app_admin_user');
			}
			
			return $this->render('registration/add.html.twig',
			                     [
				                     'controller_name' => 'Page d\'enregistrement ',
				                     'form'            => $form->createView(),
			                     ]);
		}
		
		#[Route('/login', name : 'app_login')]
		public function login(AuthenticationUtils $authenticationUtils): Response
		{
			$error = $authenticationUtils->getLastAuthenticationError();
			
			$lastUsername = $authenticationUtils->getLastUsername();
			return $this->render(
				'login/index.html.twig',
				[
					'lastUsername' => $lastUsername,
					'error'        => $error
				]
			);
		}
		
		/**
		 * @throws \Exception
		 */
		#[Route('/logout', name : 'app_logout', methods : ['GET'])]
		public function logout()
		{
			throw new Exception('Don\'t forgent to activate logout in security.yaml');
		}
	}
