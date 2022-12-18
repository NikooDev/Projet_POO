<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Quel est votre nom ?',
					])
				]
			])
			->add('firstname', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Quel est votre prénom',
					])
				]
			])
			->add('username', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez créer votre pseudo',
					])
				]
			])
			->add('plainPassword', PasswordType::class, [
				// instead of being set onto the object directly,
				// this is read and encoded in the controller
				'mapped' => false,
				'attr' => ['autocomplete' => 'new-password'],
				'constraints' => [
					new NotBlank([
						'message' => 'Choisissez un mot de passe',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Votre mot de passe doit respecter la limite de {{ limit }} caractères',
						// max length allowed by Symfony for security reasons
						'max' => 4096,
					]),
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
