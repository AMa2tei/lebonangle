<?php
	
	namespace App\EventListener;
	
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use Symfony\Component\Security\Http\Event\LogoutEvent;
	
	class LgoutSubscriber
		implements
		EventSubscriberInterface
	{
		public function __construct(
			private readonly UrlGeneratorInterface $urlGenerator
		) {}
		
		/**
		 * @inheritDoc
		 */
		public static function getSubscribedEvents(): array
		{
			return [LogoutEvent::class => 'onLogout'];
		}
		
		public function onLogout(LogoutEvent $event): void
		{
			$token    = $event->getToken();
			$request  = $event->getRequest();
			$response = $event->getResponse();
			$response = new RedirectResponse(
				$this->urlGenerator->generate('app_advert_index'),
				Response::HTTP_SEE_OTHER
			);
			$event->setResponse($response);
		}
	}