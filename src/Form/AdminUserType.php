<?php
	
	namespace App\Form;
	
	use App\Entity\AdminUser;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class AdminUserType
		extends
		AbstractType
	{
		public function buildForm(FormBuilderInterface $builder,
		                          array                $options)
		{
			$builder->add('username')
			        ->add('email')
			        ->add('plainpassword',
			              PasswordType::class)
			        ->add('submit',
			              SubmitType::class);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults(
				[
					'data_class' => AdminUser::class
				]
			);
		}
	}