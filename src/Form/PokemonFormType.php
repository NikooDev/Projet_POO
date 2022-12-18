<?php

namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PokemonFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Indiquer un nom à votre pokemon',
					])
				]
			])
			->add('type', ChoiceType::class, [
				'choices' => [
					'Dragon' => 'Dragon',
					'Électrik' => 'Électrik',
					'Combat' => 'Combat',
					'Insecte' => 'Insecte',
					'Feu' => 'Feu',
					'Vol' => 'Vol',
					'Spectre' => 'Spectre',
					'Plante' => 'Plante',
					'Sol' => 'Sol',
					'Glace' => 'Glace',
					'Normal' => 'Normal',
					'Poison' => 'Poison',
					'Psy' => 'Psy',
					'Roche' => 'Roche',
					'Eau' => 'Eau',
				]
			])
			->add('size', IntegerType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Taille requise',
					])
				]
			])
			->add('weight', IntegerType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Poids requis',
					])
				]
			])
			->add('sex', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Indiquer un sexe à votre pokemon',
					])
				]
			])
			->add('catch_rate', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Indiquer un taux de capture à votre pokemon',
					])
				]
			])
			->add('color', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Indiquer une couleur à votre pokemon',
					])
				]
			])
			->add('description', TextareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Une description est requis pour votre pokemon',
					])
				]
			])
			->add('attitude', TextareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Une attitude est requise pour votre pokemon',
					])
				]
			])
			->add('differences', TextareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Des différences sont requises pour votre pokemon',
					])
				]
			])
			->add('evolution', TextareaType::class, [
				'required' => false
			])
			->add('talent', TextareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Un talent est requis pour votre pokemon',
					])
				]
			])
			->add('image_url', FileType::class, [
				'mapped' => false,
				'multiple' => false,
				'required' => false,
				'constraints' => [
					new NotBlank([
						'message' => 'Une image est requise pour votre pokemon',
					]),
					new File([
						'maxSize' => '1024k',
						'mimeTypes' => [
							'image/jpg',
							'image/png'
						],
						'mimeTypesMessage' => 'Votre image doit être de type jpg ou png',
					])
				]
			])
			->add('num_pokedex', IntegerType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'Pokédex requis',
					])
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Pokemon::class,
		]);
	}
}
