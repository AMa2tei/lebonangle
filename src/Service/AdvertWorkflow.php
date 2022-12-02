<?php
	
	namespace App\Service;
	
	use App\Repository\AdvertRepository;
	use DateTimeImmutable;
	use Symfony\Component\Workflow\Registry;
	
	class AdvertWorkflow
	{
		public static function Publish($id,
		                               AdvertRepository $advertRepository,
		                               Registry $registry): void
		{
			$advert = $advertRepository->find($id);
			
			$workflow = $registry->get($advert);
			
			if ($workflow->can($advert,
			                   'publish')) {
				$advert->setPublishedAt(new DateTimeImmutable());
				$workflow->apply($advert,
				                 'publish');
				$workflow->getEnabledTransitions($advert);
				$advertRepository->save($advert,
				                        true);
			}
		}
		
		public static function Rejected($id,
		                                AdvertRepository $advertRepository,
		                                Registry $registry): void
		{
			$advert = $advertRepository->find($id);
			
			$workflow = $registry->get($advert);
			
			if ($workflow->can($advert,
			                   'reject')) {
				$advert->setPublishedAt(new DateTimeImmutable());
				$workflow->apply($advert,
				                 'reject');
				$workflow->getEnabledTransitions($advert);
				$advertRepository->save($advert,
				                        true);
			}
		}
	}