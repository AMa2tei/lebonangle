<?php
	
	namespace App\Serializer;
	
	use App\Entity\Picture;
	use ArrayObject;
	use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
	use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
	use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
	use Vich\UploaderBundle\Storage\StorageInterface;
	
	class PictureNormalizer
		implements
		ContextAwareNormalizerInterface,
		NormalizerAwareInterface
	{
		use NormalizerAwareTrait;
		
		private const ALREADY_CALLED = 'PICTURE_NORMALIZER_ALREADY_CALLED';
		
		public function __construct(private readonly StorageInterface $storage) {}
		
		
		/**
		 * @inheritDoc
		 */
		public function supportsNormalization(mixed  $data,
		                                      string $format = null,
		                                      array  $context = []): bool
		{
			if (isset($context[ self::ALREADY_CALLED ])) {
				return false;
			}
			return $data
			       instanceof
			       Picture;
		}
		
		/**
		 * @inheritDoc
		 */
		public function normalize(mixed  $object,
		                          string $format = null,
		                          array  $context = []): float | array | ArrayObject | bool | int | string | null
		{
			$object->contentUrl = $this->storage->resolveUri($object,
			                                                 'file');
			return $this->normalizer->normalize($object,
			                                    $format,
			                                    $context);
		}
	}